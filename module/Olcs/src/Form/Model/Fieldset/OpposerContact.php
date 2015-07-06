<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("opposerContact")
 */
class OpposerContact
{
    /**
     * @Form\AllowEmpty(true)
     * @Form\Required(true)
     * @Form\Attributes({"id":"phone","placeholder":"","class":"medium", "required":false})
     * @Form\Input("Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Options({"label":"Phone"})
     * @Form\Type("\Common\Form\Elements\InputFilters\Phone")
     * @Form\Name("phone_business")
     */
    public $phoneBusiness = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     * @Form\Name("phone_business_id")
     */
    public $phoneBusinessId = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     * @Form\Name("phone_business_version")
     */
    public $phoneBusinessVersion = null;

    /**
     * @Form\AllowEmpty(true)
     * @Form\Required(true)
     * @Form\Attributes({"id":"email","placeholder":"","class":"medium", "required":false})
     * @Form\Input("Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Options({"label":"Email"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\EmailAddress"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"max":60}})
     */
    public $email = null;
}
