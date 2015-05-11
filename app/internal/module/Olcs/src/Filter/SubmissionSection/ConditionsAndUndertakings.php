<?php

namespace Olcs\Filter\SubmissionSection;

/**
 * Class ConditionsAndUndertakings
 * @package Olcs\Filter\SubmissionSection
 */
class ConditionsAndUndertakings extends AbstractSubmissionSectionFilter
{
    /**
     * Filters data for conditions-and-undertakings section
     * @param array $data
     * @return array $dataToReturnArray
     */
    public function filter($data = array())
    {
        $dataToReturnArray = array('tables' => array('conditions' => [], 'undertakings' => []));

        // CUs attached to the case
        if (isset($data['conditionUndertakings']) && is_array($data['conditionUndertakings'])) {
            foreach ($data['conditionUndertakings'] as $entity) {
                $tableName = $entity['conditionType']['id'] == 'cdt_und' ? 'undertakings' : 'conditions';
                $dataToReturnArray['tables'][$tableName][] = $this->generateSubmissionEntity($entity, 'c'.$data['id']);
            }
        }

        // CUs attached to the application
        if (isset($data['application']['conditionUndertakings']) && is_array($data['application'])) {
            foreach ($data['application']['conditionUndertakings'] as $entity) {
                $tableName = $entity['conditionType']['id'] == 'cdt_und' ? 'undertakings' : 'conditions';
                $dataToReturnArray['tables'][$tableName][] = $this->generateSubmissionEntity($entity,
                    'a'.$data['application']['id']);
            }
        }

        // CUs attached to the licence
        if (isset($data['licence']['conditionUndertakings']) && is_array($data['licence']['conditionUndertakings'])) {
            foreach ($data['licence']['conditionUndertakings'] as $entity) {
                $tableName = $entity['conditionType']['id'] == 'cdt_und' ? 'undertakings' : 'conditions';
                $dataToReturnArray['tables'][$tableName][] = $this->generateSubmissionEntity($entity,
                    'l'.$data['licence']['id']);
            }
        }

        usort(
            $dataToReturnArray['tables']['undertakings'],
            function ($a, $b) {
                return strtotime($b['createdOn']) - strtotime($a['createdOn']);
            }
        );
        usort(
            $dataToReturnArray['tables']['conditions'],
            function ($a, $b) {
                return strtotime($b['createdOn']) - strtotime($a['createdOn']);
            }
        );

        return $dataToReturnArray;
    }

    private function generateSubmissionEntity($entity, $parentId = '')
    {
        $thisEntity = array();
        $thisEntity['id'] = $entity['id'];
        $thisEntity['version'] = $entity['version'];
        $thisEntity['createdOn'] = $entity['createdOn'];
        $thisEntity['parentId'] = $parentId;
        $thisEntity['addedVia'] = $entity['addedVia'];
        $thisEntity['isFulfilled'] = $entity['isFulfilled'];
        $thisEntity['isDraft'] = $entity['isDraft'];
        $thisEntity['attachedTo'] = $entity['attachedTo'];

        if (empty($entity['operatingCentre'])) {
            $thisEntity['OcAddress'] = [];
        } else {
            $thisEntity['OcAddress'] = $entity['operatingCentre']['address'];
        }

        return $thisEntity;
    }
}
