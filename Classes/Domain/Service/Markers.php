<?php
declare(strict_types=1);
namespace In2code\Osm\Domain\Service;

use In2code\Osm\Domain\Model\MarkerContainer;
use In2code\Osm\Exception\ConfigurationMissingException;
use In2code\Osm\Exception\RequestFailedException;
use In2code\Osm\Utility\ArrayUtility;
use In2code\Osm\Utility\DatabaseUtility;
use In2code\Osm\Utility\StringUtility;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Markers
 */
class Markers
{
    /**
     * @var EventDispatcherInterface
     */
    protected $evenDispatcher = null;

    /**
     * Markers constructor.
     * @param EventDispatcherInterface $evenDispatcher
     */
    public function __construct(EventDispatcherInterface $evenDispatcher)
    {
        $this->evenDispatcher = $evenDispatcher;
    }

    /**
     * @param int $contentIdentifier
     * @return MarkerContainer
     * @throws RequestFailedException
     * @throws ConfigurationMissingException
     */
    public function getMarkers(int $contentIdentifier): MarkerContainer
    {
        $configuration = $this->getFlexFormFromContentElement($contentIdentifier);
        if ($this->isPlugin1($contentIdentifier)) {
            $markers = $this->buildFromPi1($configuration);
        } else {
            $markers = $this->getMarkersFromAddresses($configuration);
        }
        $markers = ArrayUtility::htmlSpecialCharsOnArray($markers);
        /** @var MarkerContainer $markerContainer */
        $markerContainer = GeneralUtility::makeInstance(MarkerContainer::class, $markers, $configuration);
        $this->evenDispatcher->dispatch($markerContainer);
        return $markerContainer;
    }

    /**
     * @param array $configuration
     * @return array
     * @throws RequestFailedException
     */
    protected function buildFromPi1(array $configuration): array
    {
        $addresses = [];
        foreach ($configuration['settings']['addresses'] as $addressConfiguration) {
            if ($addressConfiguration !== []) {
                $address = $addressConfiguration['config'];
                if ($address['address'] !== '' || ($address['latitude'] !== '' && $address['longitude'] !== '')) {
                    $addresses[] = $address;
                }
            }
        }
        $addresses = $this->convertAddressesToGeoCoordinates($addresses);
        return $addresses;
    }

    /**
     * Example return value:
     *  [
     *      [
     *          'tt_address_uid' => 123,
     *          'marker' => 1,
     *          'markertitle' => 'Title',
     *          'markerdescription' => 'Description text'
     *          'latitude' => 12.1234567,
     *          'longitude' => 12.1234567
     *      ]
     *  ]
     * @param array $configuration
     * @return array
     * @throws ConfigurationMissingException
     */
    protected function getMarkersFromAddresses(array $configuration): array
    {
        $list = StringUtility::integerList($configuration['settings']['addresses']);
        if ($list === '') {
            throw new ConfigurationMissingException('No addresses configured', 1597233868);
        }
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_address');
        $records = (array)$queryBuilder
            ->select(
                'uid as tt_address_uid',
                'name as markertitle',
                'description as markerdescription',
                'latitude',
                'longitude'
            )
            ->from('tt_address')
            ->where('uid in (' . $list . ')')
            ->execute()
            ->fetchAll();
        foreach ($records as &$record) {
            if (!empty($record['markertitle'])) {
                $record['marker'] = 1;
            }
        }
        return $records;
    }

    /**
     * @param array $markers
     * @return array
     * @throws RequestFailedException
     */
    protected function convertAddressesToGeoCoordinates(array $markers): array
    {
        foreach ($markers as &$marker) {
            if (empty($marker['latitude']) && empty($marker['longitude']) && !empty($marker['address'])) {
                /** @var GeoConverter $geoConverter */
                $geoConverter = GeneralUtility::makeInstance(GeoConverter::class);
                $coordinates = $geoConverter->convertAddressToCoordinates($marker['address']);
                $marker['latitude'] = $coordinates[0];
                $marker['longitude'] = $coordinates[1];
            }
        }
        return $markers;
    }

    /**
     * @param int $contentIdentifier
     * @return array
     */
    protected function getFlexFormFromContentElement(int $contentIdentifier): array
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_content');
        $xml = $queryBuilder
            ->select('pi_flexform')
            ->from('tt_content')
            ->where('uid=' . (int)$contentIdentifier)
            ->execute()
            ->fetchColumn();
        /** @var FlexFormService $flexFormService */
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        return $flexFormService->convertFlexFormContentToArray($xml);
    }

    /**
     * @param int $contentIdentifier
     * @return bool
     */
    protected function isPlugin1(int $contentIdentifier): bool
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_content', true);
        return (string)$queryBuilder
            ->select('list_type')
            ->from('tt_content')
            ->where('uid=' . (int)$contentIdentifier . ' and CType="list"')
            ->execute()
            ->fetchColumn() === 'osm_pi1';
    }
}
