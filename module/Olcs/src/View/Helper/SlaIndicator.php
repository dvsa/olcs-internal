<?php

namespace Olcs\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class SlaIndicator
 *
 * @package Olcs\View\Helper
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class SlaIndicator extends AbstractHelper
{
    /**
     * @param $data
     * @param $targetDate
     *
     * @return string
     */
    public function __invoke()
    {
        return $this;
    }

    public function hasTargetBeenMet($date = null, $targetDate = null)
    {
        if (is_null($date) || is_null($targetDate)) {
            return '<span class="status grey">Inactive</span>';
        }

        $retVal = $this->doHasTargetBeenMet($date, $targetDate);

        if (!$retVal) {
            return '<span class="status red">Fail</span>';
        }

        return '<span class="status green">Pass</span>';
    }

    public function doHasTargetBeenMet($date = null, $targetDate = null)
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($date)));
        $targetDateTime = \DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($targetDate)));

        if ($dateTime <= $targetDateTime) {
            return true;
        }

        return false;
    }
}
