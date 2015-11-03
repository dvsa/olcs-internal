<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("fields")
 */
class Appeal extends CaseBase
{
    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Appeal deadline",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Type("DateSelect")
     * @Form\Required(false)
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date", "options":{"format":"Y-m-d"}})
     * @Form\Filter({"name": "DateSelectNullifier"})
     */
    public $deadlineDate = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Date of appeal",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Type("DateSelect")
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date", "options":{"format":"Y-m-d"}})
     * @Form\Filter({"name": "DateSelectNullifier"})
     */
    public $appealDate = null;

    /**
     * @Form\Attributes({"class":"","id":""})
     * @Form\Options({"label":"Appeal number"})
     * @Form\Required(false)
     * @Form\AllowEmpty(true)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":2,"max":20}})
     */
    public $appealNo = null;

    /**
     * @Form\Options({
     *     "label": "Reason",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     *     "category": "appeal_reason"
     * })
     * @Form\Required(true)
     * @Form\Attributes({"id":"","placeholder":"", "required":false})
     * @Form\Type("DynamicSelect")
     */
    public $reason = null;

    /**
     * @Form\Attributes({"class":"extra-long","id":""})
     * @Form\Options({"label":"Outline ground"})
     * @Form\Required(false)
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":4000}})
     */
    public $outlineGround = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Date of appeal hearing",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date", "options":{"format":"Y-m-d"}})
     * @Form\Filter({"name": "DateSelectNullifier"})
     */
    public $hearingDate = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Date of decision",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date", "options":{"format":"Y-m-d"}})
     * @Form\Filter({"name": "DateSelectNullifier"})
     */
    public $decisionDate = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Papers due at tribunal",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date", "options":{"format":"Y-m-d"}})
     * @Form\Filter({"name": "DateSelectNullifier"})
     */
    public $papersDueDate = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Papers sent date",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date", "options":{"format":"Y-m-d"}})
     * @Form\Filter({"name": "DateSelectNullifier"})
     */
    public $papersSentDate = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Required(false)
     * @Form\Options({
     *     "label": "Outcome",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     *     "category": "appeal_outcome"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $outcome = null;

    /**
     * @Form\Attributes({"class":"extra-long","id":""})
     * @Form\Options({"label":"Comments"})
     * @Form\Required(false)
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":4000}})
     */
    public $comment = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"Cancelled / Withdrawn?"})
     * @Form\Type("OlcsCheckbox")
     */
    public $isWithdrawn = null;

    /**
     * @Form\Required(true)
     * @Form\AllowEmpty(true)
     * @Form\Input("Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Withdrawn date",
     *     "create_empty_option": true,
     *     "render_delimiters": false,
     *     "hint": "Please note, all associated stay information on this case will also be withdrawn",
     *     "required": false
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "ValidateIf",
     *      "options":{
     *          "context_field": "isWithdrawn",
     *          "context_values": {"Y"},
     *          "validators": {
     *              {"name": "\Common\Validator\Date"},
     *              {"name": "Date", "options": {"format": "Y-m-d"}},
     *              {"name": "\Common\Form\Elements\Validators\DateNotInFuture"}
     *          }
     *      }
     * })
     */
    public $withdrawnDate = null;
}
