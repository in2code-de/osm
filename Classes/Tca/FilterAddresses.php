<?php
declare(strict_types=1);
namespace In2code\Osm\Tca;

use Doctrine\DBAL\Exception as ExceptionDbal;
use In2code\Osm\Utility\DatabaseUtility;
use Throwable;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use UnexpectedValueException;

class FilterAddresses
{
    /**
     * Filter addresses to records from one or more page identifiers
     * Configuration can be set via Page TSConfig:
     *  tx_osm {
     *      flexform {
     *          addressPageIdentifiers = 1,2,3
     *      }
     *  }
     *
     * @throws ExceptionDbal
     */
    public function filter(array &$params): void
    {
        $pageIdentifiers = $this->getPageIdentifiers();
        if ($pageIdentifiers !== []) {
            foreach ($params['items'] as $key => $item) {
                if (!is_int($item[1])) {
                    continue;
                }
                if ($this->isRecordInAllowedPages($item[1], $pageIdentifiers)) {
                    continue;
                }
                unset($params['items'][$key]);
            }
        }
    }

    /**
     * @throws ExceptionDbal
     */
    protected function isRecordInAllowedPages(int $addressIdentifier, array $pageIdentifiers): bool
    {
        return in_array($this->getPidOfAddressRecord($addressIdentifier), $pageIdentifiers);
    }

    /**
     * @throws ExceptionDbal
     */
    protected function getPidOfAddressRecord(int $addressIdentifier): int
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_address', true);
        return (int)$queryBuilder
            ->select('pid')
            ->from('tt_address')->where('uid=' . $addressIdentifier)->executeQuery()
            ->fetchOne();
    }

    /**
     * @throws ExceptionDbal
     */
    protected function getPageIdentifiers(): array
    {
        $configuration = BackendUtility::getPagesTSconfig($this->getCurrentPageIdentifier());
        try {
            $list = ArrayUtility::getValueByPath($configuration, 'tx_osm./flexform./pi2./addressPageIdentifiers');
            return GeneralUtility::intExplode(',', $list, true);
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * @throws ExceptionDbal
     */
    protected function getCurrentPageIdentifier(): int
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_content', true);
        return (int)$queryBuilder
            ->select('pid')
            ->from('tt_content')->where('uid=' . $this->getCurrentContentIdentifier())
            ->executeQuery()
            ->fetchOne();
    }

    protected function getCurrentContentIdentifier(): int
    {
        $parameters = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['edit'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['edit'] ?? null ?: [];
        if (!empty($parameters['tt_content']) && is_array($parameters['tt_content'])) {
            return (int)key($parameters['tt_content']);
        }
        throw new UnexpectedValueException('Could not determine content identifier', 1599738783);
    }
}
