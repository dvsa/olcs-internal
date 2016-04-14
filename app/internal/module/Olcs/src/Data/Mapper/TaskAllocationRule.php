<?php

namespace Olcs\Data\Mapper;

use Common\Data\Mapper\MapperInterface;
use Zend\Form\FormInterface;

/**
 * Class TaskAllocationRule
 * @package Olcs\Data\Mapper
 */
class TaskAllocationRule implements MapperInterface
{
    /**
     * Should map data from a result array into an array suitable for a form
     *
     * @param array $data
     */
    public static function mapFromResult(array $data)
    {
        if (!isset($data['id'])) {
            // if no ID key then must be new, therefore:
            return [];
        }

        $user = $data['user'];
        // if no user selected and task Alpha splits exists then set the user dropdown to 'alpha-split'
        if (empty($user) && is_array($data['taskAlphaSplits']) && count($data['taskAlphaSplits']) > 0) {
            $user = 'alpha-split';
        }

        $formData = [
            'details' => [
                'id' => $data['id'],
                'version' => $data['version'],
                'category' => $data['category'],
                'goodsOrPsv' => isset($data['goodsOrPsv']['id']) ? $data['goodsOrPsv']['id'] : '',
                'isMlh' => $data['isMlh'] ? 'Y' : 'N',
                'trafficArea' => $data['trafficArea'],
                'teamId' => $data['team']['id'],
                'team' => $data['team'],
                'user' => $user,
            ]
        ];

        return $formData;
    }

    /**
     * Should map form data back into a command data structure
     *
     * @param array $formData
     * @return array
     */
    public static function mapFromForm(array $formData)
    {
        $data = [
            'id' => $formData['details']['id'],
            'version' => $formData['details']['version'],
            'category' => $formData['details']['category'],
            'goodsOrPsv' => $formData['details']['goodsOrPsv'],
            'isMlh' => $formData['details']['isMlh'],
            'trafficArea' => $formData['details']['trafficArea'],
            'team' => $formData['details']['team'],
            'user' => $formData['details']['user'],
        ];

        if (empty($data['team']) && is_numeric($formData['details']['teamId'])) {
            $data['team'] = $formData['details']['teamId'];
        }

        // if Alpha Split is selected then set user to null
        if ($data['user'] === 'alpha-split') {
            $data['user'] = null;
        }
        if (empty($data['user'])) {
            unset($data['user']);
        }

        return $data;
    }

    /**
     * Should map errors onto the form, any global errors should be returned so they can be added
     * to the flash messenger
     *
     * @param FormInterface $form
     * @param array $errors
     * @return array
     * @inheritdoc
     */
    public static function mapFromErrors(FormInterface $form, array $errors)
    {
        return $errors;
    }
}
