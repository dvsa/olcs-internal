<?php

namespace Olcs\Form\Model\Fieldset\BusReg;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("bus-service-other-services")
 */
class OtherServices extends \Olcs\Form\Model\Fieldset\Base
{
    /**
     * @Form\Attributes({"class":"","id":"serviceNo"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":70}})
     */
    public $serviceNo = null;
}