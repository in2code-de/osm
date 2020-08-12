<?php
declare(strict_types=1);
namespace In2code\Osm\Hooks\PageLayoutView;

use In2code\Osm\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Plugin2PreviewRenderer
 */
class Plugin2PreviewRenderer extends AbstractPreviewRenderer
{
    /**
     * @var string
     */
    protected $listType = 'osm_pi2';

    /**
     * @return array
     */
    protected function getAssignmentsForTemplate(): array
    {
        return ['addresses' => $this->getAddresses()];
    }

    /**
     * @return array
     */
    protected function getAddresses(): array
    {
        $flexForm = $this->getFlexForm();
        $addresses = [];
        if (!empty($flexForm['settings']['addresses'])) {
            foreach (GeneralUtility::intExplode(',', $flexForm['settings']['addresses']) as $addressIdentifier) {
                $address = $this->getAddress($addressIdentifier);
                if ($address !== []) {
                    $addresses[] = $address;
                }
            }
        }
        return $addresses;
    }

    /**
     * @param int $identifier
     * @return array
     */
    protected function getAddress(int $identifier): array
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_address');
        $address = $queryBuilder
            ->select('*')
            ->from('tt_address')
            ->where('uid=' . (int)$identifier)
            ->execute()
            ->fetch();
        if ($address !== false) {
            return $address;
        }
        return [];
    }
}
