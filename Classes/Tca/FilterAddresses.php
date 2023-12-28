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
     * @param array $params
     * @return void
     * @throws ExceptionDbal
     */
    public function filter(array &$params): void
    {
        $pageIdentifiers = $this->getPageIdentifiers();
        if ($pageIdentifiers !== []) {
            foreach ($params['items'] as $key => $item) {
                if (is_int($item[1])) {
                    if ($this->isRecordInAllowedPages($item[1], $pageIdentifiers) === false) {
                        unset($params['items'][$key]);
                    }
                }
            }
        }
    }

    /**
     * @param int $addressIdentifier
     * @param array $pageIdentifiers
     * @return bool
     * @throws ExceptionDbal
     */
    protected function isRecordInAllowedPages(int $addressIdentifier, array $pageIdentifiers): bool
    {
        return in_array($this->getPidOfAddressRecord($addressIdentifier), $pageIdentifiers);
    }

    /**
     * @param int $addressIdentifier
     * @return int
     * @throws ExceptionDbal
     */
    protected function getPidOfAddressRecord(int $addressIdentifier): int
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_address', true);
        return (int)$queryBuilder
            ->select('pid')
            ->from('tt_address')->where('uid=' . (int)$addressIdentifier)->executeQuery()
            ->fetchOne();
    }

    /**
     * @return array
     * @throws ExceptionDbal
     */
    protected function getPageIdentifiers(): array
    {
        $configuration = BackendUtility::getPagesTSconfig($this->getCurrentPageIdentifier());
        try {
            $list = ArrayUtility::getValueByPath($configuration, 'tx_osm./flexform./pi2./addressPageIdentifiers');
            return GeneralUtility::intExplode(',', $list, true);
        } catch (Throwable $exception) {
            return [];
        }
    }

    /**
     * @return int
     * @throws ExceptionDbal
     */
    protected function getCurrentPageIdentifier(): int
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('tt_content', true);
        return (int)$queryBuilder
            ->select('pid')
            ->from('tt_content')->where('uid=' . (int)$this->getCurrentContentIdentifier())
            ->executeQuery()
            ->fetchOne();
    }

    protected function getCurrentContentIdentifier(): int
    {
        $parameters = GeneralUtility::_GP('edit') ?: [];
        if (!empty($parameters['tt_content']) && is_array($parameters['tt_content'])) {
            return (int)key($parameters['tt_content']);
        } else {
            throw new UnexpectedValueException('Could not determine content identifier', 1599738783);
        }
    }
}
