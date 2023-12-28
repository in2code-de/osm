<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
if (!defined('TYPO3')) {
    die('Access denied.');
}

/**
 * Register Plugins
 */
ExtensionUtility::registerPlugin(
    'osm',
    'Pi1',
    'LLL:EXT:osm/Resources/Private/Language/locallang_db.xlf:pi1.title',
    'extension-osm-icon'
);
if (ExtensionManagementUtility::isLoaded('tt_address')) {
    ExtensionUtility::registerPlugin(
        'osm',
        'Pi2',
        'LLL:EXT:osm/Resources/Private/Language/locallang_db.xlf:pi2.title',
        'extension-osm-icon'
    );
}

/**
 * Disable not needed fields in tt_content
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['osm_pi1'] = 'select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['osm_pi2'] = 'select_key,pages,recursive';

/**
 * Include Flexform
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['osm_pi1'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    'osm_pi1',
    'FILE:EXT:osm/Configuration/FlexForms/FlexFormPi1.xml'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['osm_pi2'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    'osm_pi2',
    'FILE:EXT:osm/Configuration/FlexForms/FlexFormPi2.xml'
);
