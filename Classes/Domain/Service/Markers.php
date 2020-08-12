<?php
declare(strict_types=1);
namespace In2code\Osm\Domain\Service;

use In2code\Osm\Exception\RequestFailedException;
use In2code\Osm\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Markers
 */
class Markers
{
    /**
     * @param int $contentIdentifier
     * @return array
     * @throws RequestFailedException
     */
    public function getMarkers(int $contentIdentifier): array
    {
        $configuration = $this->getFlexFormFromContentElement($contentIdentifier);
        if ($this->isPlugin1($configuration)) {
            $markers = $this->buildFromPi1($configuration);
        } else {
            throw new \LogicException('Must be implemented first', 1597227207);
        }
        $markers = $this->convertAddressesToGeoCoordinates($markers);
        return $markers;
    }

    /**
     * @param array $configuration
     * @return array
     */
    protected function buildFromPi1(array $configuration): array
    {
        return [$configuration['settings']];
    }

    /**
     * @param array $markers
     * @return array
     * @throws RequestFailedException
     */
    protected function convertAddressesToGeoCoordinates(array $markers): array
    {
        foreach ($markers as &$marker) {
            if (empty($marker['latitude']) && empty($marker['longitude'])) {
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
     * @param array $configuration
     * @return bool
     */
    protected function isPlugin1(array $configuration): bool
    {
        return isset($configuration['settings']['mode']);
    }
}