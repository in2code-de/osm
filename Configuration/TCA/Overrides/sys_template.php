<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
if (!defined('TYPO3')) {
    die('Access denied.');
}

/**
 * Add TypoScript Static Template
 */
ExtensionManagementUtility::addStaticFile(
    'osm',
    'Configuration/TypoScript/',
    'Main TypoScript'
);
