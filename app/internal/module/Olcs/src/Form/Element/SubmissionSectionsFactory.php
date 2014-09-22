<?php

namespace Olcs\Form\Element;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SubmissionSectionsFactory
 * @package Olcs\Form\Element
 */
class SubmissionSectionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $formElementManager
     * @return SubmissionSections
     */
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        /** @var \Zend\Form\FormElementManager $formElementManager */
        $serviceLocator = $formElementManager->getServiceLocator();

        $service = new SubmissionSections();

        /** @var \Common\Form\Element\DynamicSelect $submissionType */
        $submissionType = $formElementManager->get('DynamicSelect');
        $options = [
            'label' => 'Submission type',
            'category' => 'submission_type',
            'empty_option' => 'Please select',
            'disable_in_array_validator' => false,
            'help-block' => 'Please select a submission type'
        ];
        $submissionType->setOptions($options);
        $service->setSubmissionType($submissionType);

        /** @var \Common\Form\Element\Button $submissionTypeSubmit */
        $submissionTypeSubmit = $formElementManager->get('Submit');
        $options = [
            'label' => 'Select type',
            'label_attributes' => array('type' => 'submit', 'class' => 'col-sm-2'),
            'column-size' => 'sm-10',
        ];
        $submissionTypeSubmit->setOptions($options);

        $service->setSubmissionTypeSubmit($submissionTypeSubmit);

        /** @var \Common\Form\Element\SubmissionSections $submissionSections */
        $sections = $formElementManager->get('DynamicMultiCheckbox');
        $sectionOptions = [
            'label' => 'Sections',
            'category' => 'submission_section',
            'disable_in_array_validator' => false,
            'help-block' => 'Please choose your submission sections'
        ];

        $sections->setOptions($sectionOptions);

        $service->setSections($sections);

        return $service;
    }
}
