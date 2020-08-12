<?php
declare(strict_types=1);
namespace In2code\Osm\Hooks\PageLayoutView;

use In2code\Osm\Exception\ConfigurationMissingException;
use In2code\Osm\Exception\TemplateFileMissingException;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class AbstractPreviewRenderer
 */
abstract class AbstractPreviewRenderer implements PageLayoutViewDrawItemHookInterface
{
    /**
     * @var array tt_content.*
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $cType = 'list';

    /**
     * Overwrite tt_content.list_type in subclasses
     *
     * @var string
     */
    protected $listType = '';

    /**
     * @var string
     */
    protected $templatePath = 'EXT:osm/Resources/Private/Templates/PreviewRenderer/';

    /**
     * AbstractPreviewRenderer constructor.
     * @throws ConfigurationMissingException
     */
    public function __construct()
    {
        if (empty($this->cType)) {
            throw new ConfigurationMissingException('Property cType must not be empty', 1597220468);
        }
    }

    /**
     * @param PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     *
     * @return void
     * @throws TemplateFileMissingException
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    ) {
        $this->data = &$row;
        if ($this->isTypeMatching() && $this->checkTemplateFile()) {
            $drawItem = false;
            $headerContent = $this->getHeaderContent();
            $itemContent .= $this->getBodytext();
        }
    }

    /**
     * @return string
     */
    protected function getHeaderContent(): string
    {
        return '<div id="element-tt_content-' . (int)$this->data['uid']
            . '" class="t3-ctype-identifier " data-ctype="' . $this->cType . '"></div>';
    }

    /**
     * @return string
     */
    protected function getBodytext(): string
    {
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplatePathAndFilename($this->getTemplateFile());
        $flexForm = $this->getFlexForm();
        $standaloneView->assignMultiple($this->getAssignmentsForTemplate() + [
            'data' => $this->data,
            'flexForm' => $flexForm
        ]);
        return $standaloneView->render();
    }

    /**
     * Can be extended from children classes
     *
     * @return array
     */
    protected function getAssignmentsForTemplate(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getFlexForm(): array
    {
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        return $flexFormService->convertFlexFormContentToArray($this->data['pi_flexform']);
    }

    /**
     * @return bool
     */
    protected function isTypeMatching(): bool
    {
        return $this->data['CType'] === $this->cType && $this->data['list_type'] === $this->listType;
    }

    /**
     * @return bool
     * @throws TemplateFileMissingException
     */
    protected function checkTemplateFile(): bool
    {
        if (is_file($this->getTemplateFile()) === false) {
            throw new TemplateFileMissingException(
                'Expected template file for preview rendering for list_type ' . $this->listType . ' is missing',
                1597220851
            );
        }
        return true;
    }

    /**
     * Get absolute path to template file
     *
     * @return string
     */
    protected function getTemplateFile(): string
    {
        return GeneralUtility::getFileAbsFileName(
            $this->templatePath . ucfirst(GeneralUtility::underscoredToLowerCamelCase($this->listType)) . '.html'
        );
    }
}
