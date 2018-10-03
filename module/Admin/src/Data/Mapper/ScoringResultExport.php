<?php

namespace Admin\Data\Mapper;

use Common\Data\Mapper\MapperInterface;
use Zend\Form\FormInterface;

/**
 * IRHP Candidate Permit Scoring Result mapper
 *
 * @package Admin\Data\Mapper
 */
class ScoringResultExport implements MapperInterface
{

    const INTER_JOURNEY_LESS_60 = 'inter_journey_less_60';
    const INTER_JOURNEY_60_90 = 'inter_journey_60_90';
    const INTER_JOURNEY_MORE_90 = 'inter_journey_more_90';

    /*const TRAFFIC_AREA_SCOTLAND = 'M';
    const TRAFFIC_AREA_WALES = 'G';
    const TRAFFIC_AREA_NORTHERN_IRELAND = 'N';*/

    const DEVOLVED_ADMINISTRATION_TRAFFIC_AREAS = ['M', 'G', 'N'];

    /**
     * @todo: this is a repeat of the same map on EcmtPermitApplication entity. Need to stop the repetition
     * Need to stop the repetition
     * Need to stop the repetition
     */
    const INTERNATIONAL_JOURNEYS_DECIMAL_MAP = [
        self::INTER_JOURNEY_LESS_60 => 0.3,
        self::INTER_JOURNEY_60_90 => 0.75,
        self::INTER_JOURNEY_MORE_90 => 1
    ];

    /**
     * Should map data from a result array into an array suitable for a form
     *
     * @param array $data Data from query
     *
     * @return array
     * @todo: find dynamic sector name for the 'None' option instead of hardcoding it
     */
    public static function mapFromResult(array $data): array
    {
        //var_dump($data['results'][0]['irhpPermitApplication']['licence']);

        $formattedData = array();
        foreach ($data['results'] as $row) {
            $sector = $row['irhpPermitApplication']['ecmtPermitApplication']['sectors'];

            $formattedData[] = [
                'permitRef'                     => $row['irhpPermitApplication']['licence']['licNo'] . '/' . $row['irhpPermitApplication']['id'] . '/' . $row['id'],
                'organisation'                  => $row['irhpPermitApplication']['licence']['organisation']['name'],
                'applicationScore'              => $row['applicationScore'],
                'intensityOfUse'                => $row['intensityOfUse'],
                'randomFactor'                  => $row['randomFactor'],
                'randomizedScore'               => $row['randomizedScore'],
                'internationalJourneys'         => self::INTERNATIONAL_JOURNEYS_DECIMAL_MAP[$row['irhpPermitApplication']['ecmtPermitApplication']['internationalJourneys']['id']],
                'sector'                        => $sector['name'] === 'None/More than one of these sectors' ? 'N/A' : $sector['name'],
                'devolvedAdministration'        => in_array(
                    $row['irhpPermitApplication']['licence']['trafficArea']['id'],
                    self::DEVOLVED_ADMINISTRATION_TRAFFIC_AREAS
                ) ? $row['irhpPermitApplication']['licence']['trafficArea']['name'] : 'N/A',
                'result'                        => $row['successful'] ? 'Successful' : 'Unsuccessful',
                'restrictedCountriesRequested'  => self::getRestrictedCountriesRequested($row),
            ];
        }

        //var_dump($formattedData);
        return ['results' => $formattedData];
    }

    /**
     * Formats the restricted countries requested for a result row
     *
     * @param array $data Row from data from query
     *
     * @return array
     */
    private static function getRestrictedCountriesRequested($row)
    {
        if ($row['irhpPermitApplication']['ecmtPermitApplication']['hasRestrictedCountries']) {
            $restrictedCountries = '';

            foreach ($row['irhpPermitApplication']['ecmtPermitApplication']['countrys'] as $country) {
                $restrictedCountries = $restrictedCountries . ', ' . $country['countryDesc'];
            }

            return $restrictedCountries;
        }

        return 'N/A';
    }

    /**
     * Should map form data back into a command data structure
     *
     * @param array $data Data from form
     *
     * @return array
     */
    public static function mapFromForm(array $data): array
    {
        return $data['permitStockDetails'];
    }

    /**
     * Should map errors onto the form, any global errors should be returned so they can be added
     * to the flash messenger
     *
     * @param FormInterface $form   Form interface
     * @param array         $errors array response from errors
     *
     * @return array
     */
    public static function mapFromErrors(FormInterface $form, array $errors): array
    {
        return $errors;
    }
}
