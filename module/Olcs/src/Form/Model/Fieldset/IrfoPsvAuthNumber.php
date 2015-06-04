<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;
use Olcs\Form\Model\Fieldset\Base;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("irfo-psv-auth-number")
 */
class IrfoPsvAuthNumber extends Base
{
    /**
     * @Form\Attributes({"class":"","id":"irfoPsvAuthNumber"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":70}})
     */
    public $name = null;
}
