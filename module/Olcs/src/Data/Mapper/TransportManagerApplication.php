<?php

/**
 * Transport Manager Application mapper
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Data\Mapper;

/**
 * Transport Manager Application mapper
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class TransportManagerApplication
{
    public static function mapFromResultForTable(array $data)
    {
        return isset($data['extra']['tmApplications']) ? $data['extra']['tmApplications'] : [];
    }

    public static function mapFromResult(array $data)
    {
        $operatingCentres = [];
        foreach ($data['operatingCentres'] as $oc) {
            $operatingCentres[] = $oc['id'];
        }
        $details['operatingCentres'] = $operatingCentres;
        if (isset($data['tmType']['id'])) {
            $details['tmType'] = $data['tmType'];
        }
        if (isset($data['tmApplicationStatus']['id'])) {
            $details['tmApplicationStatus'] = $data['tmApplicationStatus']['id'];
        }
        $details['id'] = $data['id'];
        $details['version'] = $data['version'];
        $details['isOwner'] = $data['isOwner'];
        $details['hoursOfWeek']['hoursPerWeekContent']['hoursMon'] = $data['hoursMon'];
        $details['hoursOfWeek']['hoursPerWeekContent']['hoursTue'] = $data['hoursTue'];
        $details['hoursOfWeek']['hoursPerWeekContent']['hoursWed'] = $data['hoursWed'];
        $details['hoursOfWeek']['hoursPerWeekContent']['hoursThu'] = $data['hoursThu'];
        $details['hoursOfWeek']['hoursPerWeekContent']['hoursFri'] = $data['hoursFri'];
        $details['hoursOfWeek']['hoursPerWeekContent']['hoursSat'] = $data['hoursSat'];
        $details['hoursOfWeek']['hoursPerWeekContent']['hoursSun'] = $data['hoursSun'];
        $details['additionalInformation'] = $data['additionalInformation'];

        return [
            'details' => $details,
            'otherLicences' => $data['otherLicences'],
            'application' => $data['application']
        ];
    }

    public static function mapFromForm(array $data)
    {
        return [
            'id' => $data['details']['id'],
            'version' => $data['details']['version'],
            'tmType' => $data['details']['tmType'],
            'additionalInformation' => $data['details']['additionalInformation'],
            'hoursMon' => ($data['details']['hoursOfWeek']['hoursPerWeekContent']['hoursMon']) ?: null,
            'hoursTue' => ($data['details']['hoursOfWeek']['hoursPerWeekContent']['hoursTue']) ?: null,
            'hoursWed' => ($data['details']['hoursOfWeek']['hoursPerWeekContent']['hoursWed']) ?: null,
            'hoursThu' => ($data['details']['hoursOfWeek']['hoursPerWeekContent']['hoursThu']) ?: null,
            'hoursFri' => ($data['details']['hoursOfWeek']['hoursPerWeekContent']['hoursFri']) ?: null,
            'hoursSat' => ($data['details']['hoursOfWeek']['hoursPerWeekContent']['hoursSat']) ?: null,
            'hoursSun' => ($data['details']['hoursOfWeek']['hoursPerWeekContent']['hoursSun']) ?: null,
            'operatingCentres' => $data['details']['operatingCentres'],
            'tmApplicationStatus' => $data['details']['tmApplicationStatus'],
            'isOwner' => $data['details']['isOwner']
        ];
    }

    public static function mapFromErrors($form, array $errors)
    {
        $details = [
            'tmType',
            'additionalInformation',
            'operatingCentres',
        ];
        $hours = [
            'hoursMon',
            'hoursTue',
            'hoursWed',
            'hoursThu',
            'hoursFri',
            'hoursSat',
            'hoursSun'
        ];
        $formMessages = [];
        foreach ($errors as $field => $message) {
            if (in_array($field, $details)) {
                $formMessages['details'][$field][] = $message;
                unset($errors[$field]);
            }
            if (in_array($field, $hours)) {
                $formMessages['hoursOfWeek']['hoursPerWeekContent'][$field][] = $message;
                unset($errors[$field]);
            }
        }
        $form->setMessages($formMessages);
        return $errors;
    }
}
