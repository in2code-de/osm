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

        /**
         * Add user func for TCA fields
         */
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1600424607] = [
            'nodeName' => 'osm_pi1_information',
            'priority' => 50,
            'class' => \In2code\Osm\Tca\Information::class,
        ];
    }
);
