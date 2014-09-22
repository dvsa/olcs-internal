<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("fields")
 * @Form\Options({"label":"Prohibition defect"})
 */
class ProhibitionDefectMain
{
    /**
     * @Form\Name("defectType")
     * @Form\Attributes({"class":"extra-long","id":"defectType"})
     * @Form\Options({"label":"Defect type"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":2,"max":255}})
     */
    public $defectType = null;

    /**
     * @Form\Name("notes")
     * @Form\Attributes({"class":"extra-long","id":"notes"})
     * @Form\Options({"label":"Definition"})
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":1024}})
     */
    public $notes = null;

    /**
     * @Form\Name("prohibition")
     * @Form\Type("Hidden")
     */
    public $prohibition = null;
}
