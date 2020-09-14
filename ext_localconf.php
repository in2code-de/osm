<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function () {

        /**
         * Include Frontend Plugins
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'In2code.osm',
            'Pi1',
            [
                'Map' => 'plugin1'
            ]
        );
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_address')) {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
                'In2code.osm',
                'Pi2',
                [
                    'Map' => 'plugin2'
                ]
            );
        }

        /**
         * Add page TSConfig
         */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:osm/Configuration/TSConfig/Osm.typoscript">'
        );
    }
);
