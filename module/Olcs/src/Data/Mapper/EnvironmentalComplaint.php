<?php

namespace Olcs\Data\Mapper;

use Common\Data\Mapper\MapperInterface;
use Zend\Form\FormInterface;

/**
 * Class EnvironmentalComplaint Mapper
 * @package Olcs\Data\Mapper
 */
class EnvironmentalComplaint implements MapperInterface
{
    /**
     * Should map data from a result array into an array suitable for a form
     *
     * @param array $data
     */
    public static function mapFromResult(array $data)
    {
        $formData['fields'] = $data;

        foreach ($formData['fields'] as $key => $value) {
            if (isset($value['id'])) {
                $formData['fields'][$key] = $value['id'];
            }
        }

        // set operating centre field
        if (isset($data['ocComplaints'])) {
            $ocComplaints = [];

            foreach ($data['ocComplaints'] as $ocComplaint) {
                $ocComplaints[] = $ocComplaint['operatingCentre']['id'];
            }

            $formData['fields']['ocComplaints'] = $ocComplaints;
        }

        if (isset($data['complainantContactDetails']['person'])) {
            // set person fields
            $formData['fields']['complainantForename'] = $data['complainantContactDetails']['person']['forename'];
            $formData['fields']['complainantFamilyName'] = $data['complainantContactDetails']['person']['familyName'];
        }

        if (isset($data['complainantContactDetails']['address'])) {
            // set address fields
            $formData['address'] = $data['complainantContactDetails']['address'];
        }

        return $formData;
    }

    /**
     * Should map form data back into a command data structure
     *
     * @param array $data
     * @return array
     */
    public static function mapFromForm(array $data)
    {
        $commandData = $data['fields'];
        $commandData['complainantContactDetails'] = [
            'person' => [
                'forename' => $commandData['complainantForename'],
                'familyName' => $commandData['complainantFamilyName'],
            ],
            'address' => $data['address'],
        ];

        return $commandData;
    }

    /**
     * Should map errors onto the form, any global errors should be returned so they can be added
     * to the flash messenger
     *
     * @param FormInterface $form
     * @param array $errors
     * @return array
     */
    public static function mapFromErrors(FormInterface $form, array $errors)
    {
        return $errors;
    }
}
