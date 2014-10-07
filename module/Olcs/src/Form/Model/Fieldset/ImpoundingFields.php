<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("impounding_fields")
 */
class ImpoundingFields
{
    /**
     * @Form\Attributes({"id":"impoundingType","placeholder":"","class":"medium"})
     * @Form\Options({
     *     "label": "Impounding type",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     *     "category": "impound_type"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $impoundingType = null;

    /**
     * @Form\Attributes({"id":"applicationReceiptDate"})
     * @Form\Options({
     *     "label": "Application received",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(true)
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $applicationReceiptDate = null;

    /**
     * @Form\Attributes({"id":"vrm","placeholder":"","class":"medium"})
     * @Form\Options({
     *     "label": "Vehicle registration mark",
     *     "label_attributes": {
     *         "class": "col-sm-2"
     *     },
     *     "column-size": "sm-5",
     *     "help-block": "Between 2 and 7 characters."
     * })
     * 
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
     * @Form\Attributes({"id":"impoundingLegislationTypes","placeholder":"","multiple":"multiple","class":"extra-long"})
     * @Form\Options({
     *     "label": "Select legislation",
     *     "disable_inarray_validator": false,
     *     "help-block": "Use CTRL to select multiple",
     *     "service_name": "Olcs\Service\Data\ImpoundingLegislation",
     *     "use_groups": "false"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $impoundingLegislationTypes = null;

    /**
     * @Form\Attributes({"id":"hearingDate"})
     * @Form\Options({
     *     "label": "Hearing date",
     *     "create_empty_option": true,
     *     "render_delimiters": true,
     *     "pattern": "d MMMM y '</div><div class=""field""><label for=hearingDate>Hearing time</label>'HH:mm:ss"
     * })
     * @Form\Required(false)
     * @Form\Type("DateTimeSelect")
     * @Form\Filter({"name": "DateTimeSelectNullifier"})
     * @Form\Validator({"name": "ValidateIf",
     *      "options":{
     *          "context_field": "impoundingType",
     *          "context_values": {"impt_hearing"},
     *          "validators": {
     *              {"name": "Date", "options": {"format": "Y-m-d h:i:s"}}
     *          }
     *      }
     * })
     */
    public $hearingDate = null;

    /**
     * @Form\Required(true)
     * @Form\Attributes({"id":"piVenue","placeholder":"","class":"medium", "required":false})
     * @Form\Options({
     *     "label": "Hearing location",
     *     "service_name": "Olcs\Service\Data\PiVenue",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category"
     * })
     *
     * @Form\AllowEmpty(true)
     * @Form\Input("Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Validator({"name": "ValidateIf",
     *      "options":{
     *          "context_field": "impoundingType",
     *          "context_values": {"impt_hearing"},
     *          "allow_empty": false,
     *          "validators": {
     *              {
     *                  "name": "ValidateIf",
     *                  "options":{
     *                      "context_field": "piVenueOther",
     *                      "context_values": {""},
     *                      "allow_empty": false,
     *                      "validators": {
     *                          {"name": "\Zend\Validator\NotEmpty"}
     *                      }
     *                  }
     *              }
     *          }
     *      }
     * })
     * @Form\Type("DynamicSelect")
     */
    public $piVenue = null;

    /**
     * @Form\Attributes({"class":"medium","id":"piVenueOther"})
     * @Form\Options({"label":"Other hearing location"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    public $piVenueOther = null;

    /**
     * @Form\Attributes({"id":"presidingTc","placeholder":"","class":"medium"})
     * @Form\Required(false)
     * @Form\Options({
     *     "label": "Agreed by",
     *     "service_name": "Olcs\Service\Data\PresidingTc",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     * })
     * @Form\Type("DynamicSelect")
     */
    public $presidingTc = null;

    /**
     * @Form\Attributes({"id":"outcome","placeholder":"","class":"medium"})
     * @Form\Required(false)
     * @Form\Options({
     *     "label": "Outcome",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     *     "category": "impound_outcome"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $outcome = null;

    /**
     * @Form\Attributes({"id":"outcomeSentDate"})
     * @Form\Options({
     *     "label": "Outcome sent date",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $outcomeSentDate = null;

    /**
     * @Form\Attributes({"class":"extra-long","id":""})
     * @Form\Options({"label":"Notes/ECMS number"})
     * @Form\Required(false)
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":4000}})
     */
    public $notes = null;
}
