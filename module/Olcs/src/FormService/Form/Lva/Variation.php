<?php

/**
 * Variation Form
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\FormService\Form\Lva;

use Common\FormService\Form\Lva\Variation as CommonVariation;

/**
 * Variation Form
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class Variation extends CommonVariation
{
    public function alterForm($form)
    {
        parent::alterForm($form);

        if ($form->has('form-actions') && $form->get('form-actions')->has('save')) {
            $form->get('form-actions')->get('save')->setLabel('internal.save.button');
        }
    }
}
