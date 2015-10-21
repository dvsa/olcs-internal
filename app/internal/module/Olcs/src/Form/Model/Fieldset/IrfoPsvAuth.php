<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("main")
 */
class IrfoPsvAuth extends OrganisationBase
{
    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Service type",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a service type",
     *     "service_name": "Olcs\Service\Data\IrfoPsvAuthType"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $irfoPsvAuthType = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Validity period",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *      "value_options":{
     *          "1":"1 year",
     *          "2":"2 years",
     *          "3":"3 years",
     *          "4":"4 years",
     *          "5":"5 years"
     *      },
     * })
     * @Form\Type("Zend\Form\Element\Select")
     */
    public $validityPeriod = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Status",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a status",
     *     "category": "irfo_auth_status"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $status = null;

    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"statusHtml", "required": false, "class":"visually-hidden"})
     * @Form\Options({
     *     "label": "Status",
     * })
     * @Form\Type("Common\Form\Elements\Types\Html")
     */
    public $statusHtml;

    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"", "required": false})
     * @Form\Options({
     *     "label": "IRFO file number",
     * })
     * @Form\Type("Common\Form\Elements\Types\Html")
     */
    public $irfoFileNo = null;

    /**
     * @Form\ComposedObject({
     *      "target_object":"Olcs\Form\Model\Fieldset\IrfoPsvAuthNumber",
     *      "is_collection":true,
     *      "options":{"count":1, "label":"Authorisation number"}
     * })
     */
    public $irfoPsvAuthNumbers = null;

    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"createdOnHtml", "required": false})
     * @Form\Options({
     *     "label": "Create date",
     * })
     * @Form\Type("Common\Form\Elements\Types\Html")
     */
    public $createdOnHtml = null;

    /**
     * @Form\Attributes({"id":""})
     * @Form\Options({
     *     "label": "In force date",
     *     "create_empty_option": true,
     *     "render_delimiters": false,
     *     "max_year_delta": "+1",
     *     "min_year_delta": "-40",
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     */
    public $inForceDate;

    /**
     * @Form\Attributes({"id":"expiryDate"})
     * @Form\Options({
     *     "label": "Expiry date",
     *     "create_empty_option": true,
     *     "render_delimiters": false,
     *     "max_year_delta": "+2",
     *     "min_year_delta": "-40",
     *     "hint": "The calculated expiry date is <span id=calculatedExpiryDateText>dd/mm/yyyy</span>",
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     *
     * @Form\Validator({"name": "ValidateIf",
     *      "options":{
     *          "context_field": "expiryDate",
     *          "context_values": {"--"},
     *          "context_truth": false,
     *          "allow_empty" : true,
     *          "validators": {
     *              {"name": "Date", "options": {"format": "Y-m-d"}},
     *              {
     *                  "name": "DateCompare",
     *                  "options": {
     *                      "compare_to":"inForceDate",
     *                      "compare_to_label":"In force date",
     *                      "operator": "gte",
     *                  }
     *              }
     *          }
     *      }
     * })
     */
    public $expiryDate;

    /**
     * @Form\Attributes({"id":""})
     * @Form\Required(false)
     * @Form\Options({
     *     "label": "Application sent date",
     *     "create_empty_option": true,
     *     "render_delimiters": false,
     *     "max_year_delta": "+1",
     *     "min_year_delta": "-40",
     * })
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     */
    public $applicationSentDate;

    /**
     * @Form\Attributes({"id":""})
     * @Form\Options({"label":"Service route from"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":30}})
     */
    public $serviceRouteFrom = null;

    /**
     * @Form\Attributes({"id":""})
     * @Form\Options({"label":"Service route to"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":30}})
     */
    public $serviceRouteTo = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Journey frequency",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a journey frequency",
     *     "category": "irfo_psv_journey_freq"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $journeyFrequency = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","multiple":"multiple","class":"chosen-select-large"})
     * @Form\Required(false)
     * @Form\Options({
     *     "label": "Transit countries",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Common\Service\Data\Country",
     *     "use_groups": false
     * })
     * @Form\Type("DynamicSelect")
     */
    public $countrys = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"Application fee exempt"})
     * @Form\Type("OlcsCheckbox")
     */
    public $isFeeExemptApplication;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"Annual fee exempt"})
     * @Form\Type("OlcsCheckbox")
     */
    public $isFeeExemptAnnual;

    /**
     * @Form\Required(false)
     * @Form\Attributes({"class":"extra-long","id":"exemptionDetails", "required":false})
     * @Form\Options({"label":"Exemption reason"})
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"max":255}})
     */
    public $exemptionDetails;

    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"copiesIssuedHtml", "required": false})
     * @Form\Options({
     *     "label": "Total chargeable copies issued",
     * })
     * @Form\Type("Common\Form\Elements\Types\Html")
     */
    public $copiesIssuedHtml;

    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"copiesIssuedTotalHtml", "required": false})
     * @Form\Options({
     *     "label": "Total copies issued",
     * })
     * @Form\Type("Common\Form\Elements\Types\Html")
     */
    public $copiesIssuedTotalHtml;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"small"})
     * @Form\Options({"label": "Chargeable copies required"})
     * @Form\Type("Text")
     * @Form\Validator({"name":"Digits"})
     */
    public $copiesRequired = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"small"})
     * @Form\Options({"label": "Non-Chargeable copies required"})
     * @Form\Type("Text")
     * @Form\Validator({"name":"Digits"})
     */
    public $copiesRequiredNonChargeable = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"small","readonly":true})
     * @Form\Options({"label": "Total copies required"})
     * @Form\Type("Text")
     * @Form\Validator({"name":"Digits"})
     */
    public $copiesRequiredTotal = null;
}
