<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

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
         * Register own preview renderer in backend
         */
        $layout = 'cms/layout/class.tx_cms_layout.php';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$layout]['tt_content_drawItem']['osm_pi1'] =
            \In2code\Osm\Hooks\PageLayoutView\Plugin1PreviewRenderer::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$layout]['tt_content_drawItem']['osm_pi2'] =
            \In2code\Osm\Hooks\PageLayoutView\Plugin2PreviewRenderer::class;
    }
);
