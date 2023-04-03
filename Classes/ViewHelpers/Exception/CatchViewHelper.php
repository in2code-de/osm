<?php
declare(strict_types=1);
namespace In2code\Osm\ViewHelpers\Exception;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CatchViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function render(): string
    {
        try {
            return $this->renderChildren();
        } catch (\Exception $exception) {
            $string = '<div class="alert alert-danger" role="alert">';
            $string .= $exception->getMessage();
            $string .= ' (' . $exception->getCode() . ')';
            $string .= '</div>';
            return $string;
        }
    }
}
