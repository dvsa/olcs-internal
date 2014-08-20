<?php

/**
 * Abstract Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */

namespace Olcs\Controller;

use Common\Controller\FormActionController;

/**
 * Abstract Controller
 */
class AbstractController extends FormActionController
{
    const MAX_LIST_DATA_LIMIT = 100;

    /**
     * Gets the licence by ID.
     *
     * @param integer $id
     * @return array
     */
    public function getLicence($id)
    {
        $bundle = array(
            'properties' => 'ALL',
            'children' => array(
                'status' => array(
                    'properties' => array('id')
                ),
                'goodsOrPsv' => array(
                    'properties' => array('id')
                ),
                'licenceType' => array(
                    'properties' => array('id')
                ),
                'trafficArea' => array(
                    'properties' => 'ALL'
                ),
                'organisation' => array(
                    'properties' => 'ALL'
                )
            )
        );

        $licence = $this->makeRestCall('Licence', 'GET', array('id' => $id), $bundle);

        return $licence;
    }

    /**
     * Retrieve some data from the backend and convert it for use in
     * a select. Optionally provide some search data to filter the
     * returned data too.
     */
    protected function getListData($entity, $data = array(), $titleKey = 'name', $primaryKey = 'id', $showAll = 'All')
    {
        $data['limit'] = self::MAX_LIST_DATA_LIMIT;
        $data['sort'] = $titleKey;  // AC says always sort alphabetically
        $response = $this->makeRestCall($entity, 'GET', $data);

        if ($showAll !== false) {
            $final = array('' => 'All');
        } else {
            $final = array();
        }
        foreach ($response['Results'] as $result) {
            $key = $result[$primaryKey];
            $value = $result[$titleKey];

            $final[$key] = $value;
        }
        return $final;
    }
}