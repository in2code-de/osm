<?php
declare(strict_types=1);
namespace In2code\Osm\EventListener\PreviewRenderer;

use Doctrine\DBAL\Exception as ExceptionDbal;
use In2code\Osm\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Plugin2PreviewRenderer extends AbstractPreviewRenderer
{
    protected string $listType = 'osm_pi2';

    /**
     * @return array[]
     * @throws ExceptionDbal
     */
    protected function getAssignmentsForTemplate(): array
    {
        return ['addresses' => $this->getAddresses()];
    }

    /**
     * @return array
     * @throws ExceptionDbal
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
     * @throws ExceptionDbal
     */
    protected function getAddress(int $identifier): array
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_address');
        $address = $queryBuilder
            ->select('*')
            ->from('tt_address')->where('uid=' . (int)$identifier)
            ->executeQuery()
            ->fetchAssociative();
        if ($address !== false) {
            return $address;
        }
        return [];
    }
}
