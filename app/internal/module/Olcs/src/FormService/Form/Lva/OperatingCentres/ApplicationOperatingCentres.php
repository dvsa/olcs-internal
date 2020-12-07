<?php

/**
 * Application Operating Centres
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\FormService\Form\Lva\OperatingCentres;

use Common\FormService\Form\Lva\OperatingCentres\AbstractOperatingCentres;
use Common\RefData;
use Laminas\Form\Form;

/**
 * Application Operating Centres
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ApplicationOperatingCentres extends AbstractOperatingCentres
{
    protected function alterForm(Form $form, array $params)
    {
        $this->getFormServiceLocator()->get('lva-application')->alterForm($form);

        parent::alterForm($form, $params);
    }

    /**
     * @see AbstractOperatingCentres::allowChangingTrafficArea
     */
    protected function allowChangingTrafficArea($trafficAreaId)
    {
        // Traffic area can be changed as long as its not Northern Irelend
        return ($trafficAreaId !== RefData::NORTHERN_IRELAND_TRAFFIC_AREA_CODE);
    }

    /**
     * @see AbstractOperatingCentres::removeTrafficAreaElements
     */
    protected function removeTrafficAreaElements($data)
    {
        return false;
    }
}
