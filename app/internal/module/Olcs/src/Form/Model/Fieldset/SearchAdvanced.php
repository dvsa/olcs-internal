<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("search-advanced")
 * @Form\Options({"label":"Advanced search","class":"extra-long"})
 */
class SearchAdvanced
{
    /**
     * @Form\Attributes({"id":"","class":"extra-long"})
     * @Form\Options({
     *     "label": "Address",
     *     "label_attributes": {
     *         "class": "col-sm-2"
     *     },
     *     "column-size": "sm-6",
     *     "help-block": "You can type anything in this box."
     * })
     * @Form\Type("\Zend\Form\Element\Textarea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Filter({"name":"Zend\Filter\StringToLower"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":10,"max":100}})
     */
    public $address = null;

    /**
     * @Form\Attributes({"class":"long","id":""})
     * @Form\Options({"label":"Town"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $town = null;

    /**
     * @Form\Attributes({"class":"medium","id":""})
     * @Form\Options({"label":"Case number"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $caseNumber = null;

    /**
     * @Form\Attributes({"class":"medium","id":""})
     * @Form\Options({"label":"Transport manager ID"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $transportManagerId = null;

    /**
     * @Form\Attributes({"class":"medium","id":""})
     * @Form\Options({"label":"Operator ID"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $operatorId = null;

    /**
     * @Form\Attributes({"class":"medium","id":""})
     * @Form\Options({"label":"Vehicle registration mark"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $vehicleRegMark = null;

    /**
     * @Form\Attributes({"class":"medium","id":""})
     * @Form\Options({"label":"Disc serial number"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $diskSerialNumber = null;

    /**
     * @Form\Attributes({"class":"medium","id":""})
     * @Form\Options({"label":"Fabs reference"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $fabsRef = null;

    /**
     * @Form\Attributes({"class":"medium","id":""})
     * @Form\Options({"label":"Company number"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $companyNo = null;
}
