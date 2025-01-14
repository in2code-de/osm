<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

if (ExtensionManagementUtility::isLoaded('tt_address')) {
    $GLOBALS['TCA']['tt_address']['columns']['description']['config']['enableRichtext'] = false;
}
