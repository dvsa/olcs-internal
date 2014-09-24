<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("fields")
 */
class ProhibitionFields
{
    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Prohibition date",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $prohibitionDate = null;

    /**
     * @Form\Attributes({"class":"","id":""})
     * @Form\Options({"label":"Vehicle registration mark"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Filter({"name":"Zend\Filter\StringToUpper"})
     * @Form\Filter({
     *     "name": "Zend\Filter\PregReplace",
     *     "options": {
     *         "pattern": "/\ /",
     *         "replacement": ""
     *     }
     * })
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":2,"max":7}})
     * @Form\Validator({"name":"Zend\I18n\Validator\Alnum"})
     */
    public $vrm = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"Trailer"})
     * @Form\Type("checkbox")
     */
    public $isTrailer = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Type",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     *     "category": "prohibition_type"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $prohibitionType = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Date cleared",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $clearedDate = null;

    /**
     * @Form\Attributes({"class":"","id":""})
     * @Form\Options({"label":"Location prohibition issued"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":2,"max":255}})
     */
    public $imposedAt = null;
}
