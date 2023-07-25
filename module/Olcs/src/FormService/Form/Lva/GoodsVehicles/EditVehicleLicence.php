<?php

namespace Olcs\FormService\Form\Lva\GoodsVehicles;

use Common\Service\Helper\FormHelperService;

/**
 * Edit Vehicle Licence
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class EditVehicleLicence
{
    protected FormHelperService $formHelper;

    public function __construct(FormHelperService $formHelper)
    {
        $this->formHelper = $formHelper;
    }

    public function getForm($request, $params = [])
    {
        $form = $this->formHelper->createFormWithRequest('Lva\EditGoodsVehicleLicence', $request);

        $this->alterForm($form, $params);

        return $form;
    }

    protected function alterForm($form, $params)
    {
        if ($params['isRemoved']) {
            $this->formHelper->disableElements($form->get('data'));
            $this->formHelper->disableElements($form->get('licence-vehicle'));

            $this->formHelper->enableElements($form->get('licence-vehicle')->get('removalDate'));
            $this->formHelper->enableElements($form->get('data')->get('version'));
            $form->get('licence-vehicle')->get('removalDate')->setShouldCreateEmptyOption(false);
        }
    }
}
