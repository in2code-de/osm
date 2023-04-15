<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

$GLOBALS['TCA']['tt_address']['columns']['description']['config']['enableRichtext'] = false;
