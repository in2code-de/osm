<?php
declare(strict_types=1);
namespace In2code\Osm\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StringUtility
 */
class StringUtility
{
    /**
     * @param string $list
     * @return string
     */
    public static function integerList(string $list): string
    {
        return implode(',', GeneralUtility::intExplode(',', $list));
    }
}
