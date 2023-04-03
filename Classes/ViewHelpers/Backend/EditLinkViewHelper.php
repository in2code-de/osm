<?php
declare(strict_types=1);
namespace In2code\Osm\ViewHelpers\Backend;

use In2code\Osm\Utility\BackendUtility;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class EditLinkViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('identifier', 'int', 'Identifier', true);
        $this->registerArgument('table', 'string', 'Tablename', false, 'tt_content');
    }

    public function render(): string
    {
        $string = '<a href="';
        $string .= BackendUtility::createEditUri($this->arguments['table'], (int)$this->arguments['identifier']);
        $string .= '" class="in2template_editlink">';
        $string .= $this->renderChildren();
        $string .= '</a>';
        return $string;
    }
}
