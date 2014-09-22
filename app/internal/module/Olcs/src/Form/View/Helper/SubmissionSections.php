<?php

namespace Olcs\Form\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;
use Zend\I18n\Translator\TranslatorInterface as Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\Form\Exception;
use Olcs\Form\Element\SubmissionSections  as SubmissionSectionsElement;

/**
 * View helper to render the submission sections element
 *
 */
class SubmissionSections extends AbstractHelper
{

    /**
     * Render a SubmissionSections element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     * @return string
     */
    public function __invoke(ElementInterface $element)
    {
        if (!$element instanceof SubmissionSectionsElement) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type Olcs\Form\Element\SubmissionSections',
                    __METHOD__
                )
            );
        }

        $formSelectPlugin = $this->view->plugin('formSelect');
        $buttonPlugin = $this->view->plugin('formButton');

        $multiCheckboxPlugin = $this->view->plugin('formSubmissionSectionsMultiCheckbox');

        if (empty($element->getSubmissionType()->getValue())) {
            // dont render sections
            return  $formSelectPlugin->render($element->getSubmissionType()) . '<br /><br />' .
            $buttonPlugin->render($element->getSubmissionTypeSubmit());
        }

        $multiCheckboxPlugin->setSeparator('</div><div class="field--two-col">');
        $markup = $formSelectPlugin->render($element->getSubmissionType()) . '<br /><br />' .
                $buttonPlugin->render($element->getSubmissionTypeSubmit()) . '<br /><br />' .
                '<div class="field--two-col">' .
                    $multiCheckboxPlugin->render($element->getSections()) .
                '</div>';
        $multiCheckboxPlugin->setSeparator('');
        return $markup;

    }
}
