<?php
declare(strict_types=1);
namespace In2code\Osm\Hooks\PageLayoutView;

use Doctrine\DBAL\Exception as ExceptionDbal;
use In2code\Osm\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Plugin2PreviewRenderer
 * Todo: Can be removed when TYPO3 11 support is dropped
 */
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
     * @throws ExceptionDbal
     */
    protected function getAddress(int $identifier): array
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_address');
        $address = $queryBuilder
            ->select('*')
            ->from('tt_address')->where('uid=' . $identifier)
            ->executeQuery()
            ->fetchAssociative();
        if ($address !== false) {
            return $address;
        }

        return [];
    }
}
