<?php
declare(strict_types = 1);
namespace In2code\Osm\Tca;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class Information
 */
class Information extends AbstractFormElement
{
    /**
     * @var string
     */
    protected $label = 'LLL:EXT:osm/Resources/Private/Language/locallang_db.xlf:pi1.information';

    /**
     * @return array
     */
    public function render()
    {
        $result = $this->initializeResultArray();
        if ($this->isNew()) {
            $content = '<div class="alert alert-warning" role="alert" style="margin-bottom: 30px;">';
            $content .= LocalizationUtility::translate($this->label);
            $content .= '</div>';
            $result['html'] = $content;
        }
        return $result;
    }

    /**
     * @return bool
     */
    protected function isNew(): bool
    {
        return $this->data['command'] === 'new';
    }
}
