<?php

namespace Olcs\Data\Mapper;

use Common\Data\Mapper\MapperInterface;
use Zend\Form\FormInterface;

/**
 * Class Impounding Mapper
 * @package Olcs\Data\Mapper
 */
class Impounding implements MapperInterface
{
    /**
     * Should map data from a result array into an array suitable for a form
     *
     * @param array $data
     */
    public static function mapFromResult(array $data)
    {
        $formData['fields'] = $data;

        // must have a case
        $formData['base']['case'] = $data['case'];

        // optionally set id and version for updates
        if (isset($data['id'])) {
            $formData['base']['id'] = $data['id'];
            $formData['base']['version'] = $data['version'];
        }

        foreach ($formData['fields'] as $key => $value) {
            if (isset($value['id'])) {
                $formData['fields'][$key] = $value['id'];
            }
        }

        if (isset($data['fields']['piVenueOther']) && $data['fields']['piVenueOther'] != '') {
            $data['fields']['piVenue'] = 'other';
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
        if ($data['fields']['piVenue'] != 'other') {
            $data['fields']['piVenueOther'] = null;
        }

        // must have a case
        $data['fields']['case'] = $data['base']['case'];

        // optionally add id and version for updates
        if (!empty($data['base']['id'])) {
            $data['fields']['id'] = $data['base']['id'];
            $data['fields']['version'] = $data['base']['version'];
        }

        $data = $data['fields'];

        return $data;
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

        return $errors['messages'];
    }
}
