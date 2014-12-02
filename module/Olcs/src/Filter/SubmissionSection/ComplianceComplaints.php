<?php

namespace Olcs\Filter\SubmissionSection;

/**
 * Class ComplianceComplaints
 * @package Olcs\Filter\SubmissionSection
 */
class ComplianceComplaints extends AbstractSubmissionSectionFilter
{
    /**
     * Filters data for compliance-complaints section
     * @param array $data
     * @return array $data
     */
    public function filter($data = array())
    {
        $filteredData = array();

        usort(
            $data,
            function ($a, $b) {
                return strtotime($b['complaintDate']) - strtotime($a['complaintDate']);
            }
        );
        $filteredData['tables']['compliance-complaints'] = $data;
        return $filteredData;
    }
}
