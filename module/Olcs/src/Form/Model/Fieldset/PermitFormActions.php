<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("form-actions")
 * @Form\Attributes({"class":"actions-container"})
 */
class PermitFormActions
{
    /**
     * @Form\Attributes({"type":"submit","class":"action--primary large"})
     * @Form\Options({"label": "Save"})
     * @Form\Type("\Common\Form\Elements\InputFilters\ActionButton")
     */
    public $save = null;


    /**
     * @Form\Attributes({"type":"submit","class":"action--secondary","id":"backToPermitList"})
     * @Form\Options({"label": "Back"})
     * @Form\Type("\Common\Form\Elements\InputFilters\ActionButton")
     */
    public $back = null;

    /**
     * @Form\Attributes({"type":"submit","class":"action--primary large visually-hidden", "id": "submitPermitApplication"})
     * @Form\Options({"label": "Submit Application"})
     * @Form\Type("\Common\Form\Elements\InputFilters\ActionButton")
     */
    public $submit = null;

    /**
     * @Form\Attributes({"type":"submit","class":"action--delete action--secondary large visually-hidden", "id": "withdrawPermitApplication"})
     * @Form\Options({"label": "Withdraw Application"})
     * @Form\Type("\Common\Form\Elements\InputFilters\ActionButton")
     */
    public $withdraw = null;

    /**
     * @Form\Attributes({"type":"submit","class":"action--delete action--secondary large visually-hidden", "id": "cancelPermitApplication"})
     * @Form\Options({"label": "Cancel"})
     * @Form\Type("\Common\Form\Elements\InputFilters\ActionButton")
     */
    public $cancel = null;
}
