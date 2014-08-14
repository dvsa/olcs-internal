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
    /**
     * Gets the licence by ID.
     *
     * @param integer $id
     * @return array
     */
    public function getLicence($id)
    {
        $bundle = array(
            'children' => array(
                'goodsOrPsv' => array(
                    'properties' => array('id')
                ),
                'status' => array(
                    'properties' => 'ALL',
                ),
                'organisation' => array(
                    'properties' => 'ALL',
                ),
                'licenceType' => array(
                    'properties' => 'ALL',
                )
            )
        );

        $licence = $this->makeRestCall('Licence', 'GET', array('id' => $id), $bundle);

        return $licence;
    }
}