<?php

namespace Olcs\Service\Utility;

/**
 * Class DateUtility
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
class DateUtility
{
    /**
     * Calculate Out of representation date
     *
     * @param array $application
     * @return array
     */
    public function calculateOor($application)
    {
        if (isset($application['operatingCentres'][0]['adPlacedDate'])) {
            $operatingCentres = $application['operatingCentres'];

            usort(
                $operatingCentres,
                function ($a, $b) {
                    return strtotime($b['adPlacedDate']) - strtotime($a['adPlacedDate']);
                }
            );

            if (!empty($operatingCentres[0]['adPlacedDate'])) {
                $newsDateObj = new \DateTime($operatingCentres[0]['adPlacedDate']);
                $oor = $this->getDateTimeProcessor()->calculateDate($newsDateObj, 21, false, false);
                $oorDate = new \DateTime($oor);

                return !empty($oorDate) ? $oorDate->format(\DateTime::ISO8601) : '';
            }
        }
        return '';
    }

    /**
     * Calculate the Out of Representation date
     *
     * @param $application
     * @return string
     */
    public function calculateOoo($application)
    {
        $latestPublication = $this->getLatestPublication($application);

        if (isset($latestPublication['pubDate']) && !empty($latestPublication['pubDate'])) {
            $pubDateObj = new \DateTime($latestPublication['pubDate']);

            $ooo = $this->getDateTimeProcessor()->calculateDate($pubDateObj, 21, false, false);
            $oooDate = new \DateTime($ooo);

            return !empty($oooDate) ? $oooDate->format(\DateTime::ISO8601) : '';
        }
        return '';
    }


    /**
     * Gets the latest publication for an application. (used to calculate OOO date)
     *
     * @param $application
     * @return array|null
     */
    private function getLatestPublication($application)
    {
        if (isset($application['publicationLinks']) && !empty($application['publicationLinks'])) {
            $publications = array();
            foreach ($application['publicationLinks'] as $pub) {

                if (isset($pub['publication'])) {
                    $publications[] = $pub['publication'];
                }
            }
            usort(
                $publications,
                function ($a, $b) {
                    return strtotime($b['pubDate']) - strtotime($a['pubDate']);
                }
            );
            return $publications[0];
        }
        return null;
    }

    /**
     * Set DateTimeProcessor
     *
     * @param \Common\Util\DateTimeProcessor $dateTimeProcessor
     * @return $this
     */
    public function setDateTimeProcessor(\Common\Util\DateTimeProcessor $dateTimeProcessor)
    {
        $this->dateTimeProcessor = $dateTimeProcessor;
        return $this;
    }

    /**
     * Get DateTimeProcessor
     *
     * @return \Common\Util\DateTimeProcessor
     */
    public function getDateTimeProcessor()
    {
        return $this->dateTimeProcessor;
    }
}
