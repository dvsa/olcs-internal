<?php

namespace Olcs\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * View helper to render the submission section
 *
 */
class SubmissionSectionMultipleTables extends AbstractHelper
{
    const DEFAULT_VIEW = 'partials/submission-table';

    /**
     * View map
     *
     * @var array
     */
    protected $viewMap = array();

    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;

    /**
     * Renders the data for a SubmissionSection details $data expected consists of multidimentional array:
     * [
     * <tableName> => <tableData>,
     * <tableName> => <tableData>
     * ...
     * ]
     *
     * @param String $submissionSection
     * @param Array $data
     * @param bool $readonly
     * @return string
     */
    public function __invoke($submissionSection = '', $data = array(), $readonly = false)
    {
        if (empty($submissionSection)) {
            return '';
        }

        return $this->render($submissionSection, $data, $readonly);
    }

    /**
     * Renders the data for a SubmissionSection details
     *
     * @param String $submissionSection
     * @param Array $data
     * @param bool $readonly
     *
     * @return string
     */
    public function render($submissionSection, $data, $readonly)
    {
        $html = '';

        $viewTemplate = isset($this->viewMap[$submissionSection]) ?
            $this->viewMap[$submissionSection] : self::DEFAULT_VIEW;

        $tableViewHelper = $this->getView()->plugin('SubmissionSectionTable');

        $tables = isset($data['data']['tables']) ?
            $data['data']['tables'] : [];
        foreach ($tables as $subSection => $tableData) {
            $html .= $tableViewHelper(
                $subSection,
                [
                    'description' => $this->getTranslator()->translate($data['sectionId'] . '-' . $subSection),
                    'data' => $data['data']
                ],
                $readonly
            );
        }

        $data['tables'] = $html;

        // config set to remove the section header if an overview already has it
        if (isset($data['config']['show_multiple_tables_section_header']) &&
            $data['config']['show_multiple_tables_section_header'] == false
        ) {
            return $html;
        }

        return $this->getView()->render($viewTemplate, ['data' => $data]);

    }

    /**
     * @param \Zend\I18n\Translator\Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Zend\I18n\Translator\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }
}
