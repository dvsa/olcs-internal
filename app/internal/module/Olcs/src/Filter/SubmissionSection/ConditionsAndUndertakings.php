<?php

namespace Olcs\Filter\SubmissionSection;

use Common\Exception\ResourceNotFoundException;
use Zend\Filter\AbstractFilter;

/**
 * Class ConditionsAndUndertakings
 * @package Olcs\Filter\SubmissionSection
 */
class ConditionsAndUndertakings extends AbstractFilter
{
    /**
     * @param \Zend\Stdlib\ArrayObject $data
     * @return \Zend\Stdlib\ArrayObject
     * @throws ResourceNotFoundException
     */
    public function filter($data = array())
    {
        $dataToReturnArray = array();
        if (isset($data['conditionUndertakings']) && is_array($data['conditionUndertakings'])) {

            usort(
                $data['conditionUndertakings'],
                function ($a, $b) {
                    return strtotime($b['createdOn']) - strtotime($a['createdOn']);
                }
            );

            foreach ($data['conditionUndertakings'] as $entity) {
                $thisEntity = array();
                $thisEntity['id'] = $entity['id'];
                $thisEntity['version'] = $entity['version'];
                $thisEntity['createdOn'] = $entity['createdOn'];
                $thisEntity['caseId'] = $entity['case']['id'];
                $thisEntity['addedVia'] = $entity['addedVia'];
                $thisEntity['isFulfilled'] = $entity['isFulfilled'];
                $thisEntity['isDraft'] = $entity['isDraft'];
                $thisEntity['attachedTo'] = $entity['attachedTo'];

                if (empty($entity['operatingCentre'])) {
                    $thisEntity['OcAddress'] = [];
                } else {
                    $thisEntity['OcAddress'] = $entity['operatingCentre']['address'];
                }
                $tableName = $entity['conditionType']['id'] == 'cdt_und' ? 'undertakings' : 'conditions';
                $dataToReturnArray[$tableName][] = $thisEntity;
            }
        }

        return $dataToReturnArray;
    }
}
