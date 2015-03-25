<?php

namespace Admin\Form\Model\Form;

use Zend\Form\Annotation as Form;
use Olcs\Form\Model\Fieldset\Base;

/**
 * @Form\Name("admin-my-details")
 * @Form\Attributes({"method":"post"})
 * @Form\Type("Common\Form\Form")
 * @Form\Options({"prefer_form_input_filter": true, "label": "My details"})
 */
class MyDetails extends Base
{
    /**
     * @Form\Name("userDetails")
     * @Form\ComposedObject("Admin\Form\Model\Fieldset\UserDetails")
     */
    public $userDetails = null;

    /**
     * @Form\Name("userContact")
     * @Form\ComposedObject("Admin\Form\Model\Fieldset\UserContact")
     */
    public $userContact = null;

    /**
     * @Form\Name("officeAddress")
     * @Form\ComposedObject("Admin\Form\Model\Fieldset\OfficeAddress")
     */
    public $officeAddress = null;

    /**
     * @Form\Name("form-actions")
     * @Form\Attributes({"class":"actions-container"})
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\CancelFormActions")
     */
    public $formActions = null;
}
