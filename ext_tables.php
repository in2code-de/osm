<?php
defined('TYPO3_MODE') || die();

call_user_func(
    function () {

        /**
         * Register Icons
         */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        $iconRegistry->registerIcon(
            'extension-osm-icon',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:osm/Resources/Public/Icons/Extension.svg']
        );

        /**
         * Register own preview renderer for content elements
         */
        $layout = 'cms/layout/class.tx_cms_layout.php';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$layout]['tt_content_drawItem']['ce.stage'] =
            \In2code\Osm\Hooks\PageLayoutView\Plugin1PreviewRenderer::class;
    }
);
