<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

if (($GLOBALS['TCA']['tt_address']['columns']['description']['config']['enableRichtext'] ?? null) !== null) {
    $GLOBALS['TCA']['tt_address']['columns']['description']['config']['enableRichtext'] = false;
}
