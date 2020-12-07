<?php

namespace Olcs\FormService\Form\Lva;

use Laminas\Form\Form;
use Common\FormService\Form\Lva\TypeOfLicence\ApplicationTypeOfLicence as CommonApplicationTypeOfLicence;

/**
 * Application Type Of Licence
 */
class ApplicationTypeOfLicence extends CommonApplicationTypeOfLicence
{
    /**
     * Make form alterations
     *
     * @param \Laminas\Form\Form $form
     * @param array $params
     * @return \Laminas\Form\Form
     */
    protected function alterForm(Form $form, $params = [])
    {
        parent::alterForm($form, $params);

        $form->get('form-actions')->get('save')->setLabel('internal.save.button');
        $form->get('type-of-licence')->remove('difference'); // removes guidance text

        return $form;
    }
}
