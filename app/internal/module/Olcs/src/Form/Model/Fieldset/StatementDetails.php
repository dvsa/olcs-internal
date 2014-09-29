<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("details")
 * @Form\Options({"label":"Statement Details"})
 */
class StatementDetails extends CaseBase
{
    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Statement type",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a statement type",
     *     "category": "statement_type"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $statementType = null;

    /**
     * @Form\Options({"label":"Vehicle registration mark"})
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
     * @Form\Attributes({"placeholder":""})
     * @Form\Options({"label":"Requestors first name"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":2,"max":35}})
     */
    public $requestorsForename = null;

    /**
     * @Form\Attributes({"placeholder":""})
     * @Form\Options({"label":"Requestors last name"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":2,"max":35}})
     */
    public $requestorsFamilyName = null;

    /**
     * @Form\Attributes({"class":"","id":""})
     * @Form\Options({"label":"Requestor body"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":2,"max":40}})
     */
    public $requestorsBody = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Date stopped",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     * @Form\Validator({
     *      "name": "DateCompare",
     *      "options": {"compare_to":"requestedDate", "compare_to_label":"Date requested", "operator": "lte"}
     * })
     */
    public $stoppedDate = null;

    /**
     * @Form\Attributes({"id":"dob"})
     * @Form\Options({
     *     "label": "Date requested",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $requestedDate = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Request mode",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     *     "category": "contact_method"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\AllowEmpty(true)
     */
    public $contactType = null;

    /**
     * @Form\Attributes({"id":"","class":"extra-long"})
     * @Form\Options({
     *     "label": "Authorised decision",
     *     "label_attributes": {
     *         "class": ""
     *     },
     *     "column-size": "",
     *     "help-block": ""
     * })
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":4000}})
     */
    public $authorisersDecision = null;
}
