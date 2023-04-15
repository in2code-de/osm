<?php
declare(strict_types=1);
namespace In2code\Osm\EventListener\PreviewRenderer;

use In2code\Osm\Exception\ConfigurationMissingException;
use In2code\Osm\Exception\TemplateFileMissingException;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

abstract class AbstractPreviewRenderer
{
    /**
     * @var array tt_content.*
     */
    protected array $data = [];

    /**
     * Overwrite tt_content.list_type in subclasses
     *
     * @var string
     */
    protected string $listType = '';

    protected string $cType = 'list';
    protected string $table = 'tt_content';
    protected string $templatePath = 'EXT:osm/Resources/Private/Templates/PreviewRenderer/';

    public function __construct()
    {
        if (empty($this->cType)) {
            throw new ConfigurationMissingException('Property cType must not be empty', 1597220468);
        }
    }

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        $this->data = $event->getRecord();
        if ($this->isTypeMatching($event) && $this->checkTemplateFile()) {
            $event->setPreviewContent($this->getBodytext());
        }
    }

    protected function getBodytext(): string
    {
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplatePathAndFilename($this->getTemplateFile());
        $flexForm = $this->getFlexForm();
        $standaloneView->assignMultiple($this->getAssignmentsForTemplate() + [
            'data' => $this->data,
            'flexForm' => $flexForm,
            'cType' => $this->cType,
            'list_type' => $this->listType,
            'table' => $this->table,
            'disableHeader' => true, // Should not be rendered (because same HTML template is used also for TYPO3 11)
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

    protected function getFlexForm(): array
    {
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        return $flexFormService->convertFlexFormContentToArray($this->data['pi_flexform']);
    }

    protected function isTypeMatching(PageContentPreviewRenderingEvent $event): bool
    {
        return $event->getTable() === $this->table
            && $this->data['CType'] === $this->cType
            && $this->data['list_type'] === $this->listType;
    }

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

    protected function getTemplateFile(): string
    {
        return GeneralUtility::getFileAbsFileName(
            $this->templatePath . GeneralUtility::underscoredToUpperCamelCase($this->listType) . '.html'
        );
    }
}
