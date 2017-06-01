<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("fields")
 * @Form\Options({"label":""})
 */
class PublicInquiryRegisterDecisionMain
{
    /*
     * @Form\Attributes({"class":"","id":"", "disabled":true})
     * @Form\Options({"label":"PI number"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    //public $piNumber = null;

    /*
     * @Form\Attributes({"id":"","placeholder":"","class":"medium", "disabled":true})
     * @Form\Options({
     *     "label": "Venue",
     *     "value_options": {
     *
     *     },
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false
     * })
     * @Form\Required(false)
     * @Form\Type("\Zend\Form\Element\Select")
     */
    //public $venue = null;

    /*
     * @Form\Attributes({"class":"long","id":"", "disabled":true})
     * @Form\Options({"label":"Other venue"})
     * @Form\Required(false)
     * @Form\Type("Text")
     */
    //public $otherVenue = null;

    /*
     * @Form\Attributes({"id":"","class":"long", "disabled":true})
     * @Form\Options({
     *     "label": "Date of PI",
     *     "create_empty_option": false,
     *     "render_delimiters": "d m y",
     *     "day_attributes": {"disabled":true},
     *     "month_attributes": {"disabled":true},
     *     "year_attributes": {"disabled":true}
     * })
     * @Form\Type("Common\Form\Elements\Custom\DateSelect")
     * @Form\Required(false)
     */
    //public $piDate = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"medium"})
     * @Form\Options({
     *     "label": "Presiding TC/DTC/HTRU/DHTRU",
     *     "service_name": "Olcs\Service\Data\PresidingTc",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false
     * })
     * @Form\Type("DynamicSelect")
     */
    public $decidedByTc = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"medium"})
     * @Form\Options({
     *     "label": "Presiding TC/DTC/HTRU/DHTRU role",
     *     "category": "tc_role",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false
     * })
     * @Form\Type("DynamicSelect")
     */
    public $decidedByTcRole = null;

    /*
     * @Form\Attributes({"class":"long tall", "id":"", "disabled":true, "multiple" : true})
     * @Form\Required(false)
     * @Form\Options({
     *     "label":"Reason for PI",
     *     "service_name": "Olcs\Service\Data\PublicInquiryReason",
     *     "disable_inarray_validator": false,
     *     "use_groups":true
     * })
     * @Form\Type("DynamicSelect")
     */
    //public $reasons = null;

    /**
     * @Form\Required(true)
     * @Form\Type("DynamicSelect")
     * @Form\Attributes({"id":"","placeholder":"","class":"chosen-select-large",  "multiple" : true,
     *     "required": false})
     * @Form\Options({
     *     "label": "Decisions",
     *     "service_name": "Olcs\Service\Data\PublicInquiryDecision",
     *     "disable_inarray_validator": false,
     *     "use_groups":true
     * })
     */
    public $decisions = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"Licence revoked at PI"})
     * @Form\Type("OlcsCheckbox")
     */
    public $licenceRevokedAtPi = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"Licence suspended at PI"})
     * @Form\Type("OlcsCheckbox")
     */
    public $licenceSuspendedAtPi = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"Licence curtailed at PI"})
     * @Form\Type("OlcsCheckbox")
     */
    public $licenceCurtailedAtPi = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"TM called with operator?"})
     * @Form\Type("OlcsCheckbox")
     */
    public $tmCalledWithOperator;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"chosen-select-large",  "multiple" : true,
     *     "required": false})
     * @Form\AllowEmpty(true)
     * @Form\Input("\Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Required(false)
     * @Form\Options({
     *     "label":"TM decision",
     *     "disable_inarray_validator": false,
     *     "category":"pi_tm_decision"
     * })
     *
     * @Form\Type("DynamicSelect")
     */
    public $tmDecisions = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"small"})
     * @Form\Options({"label": "Number of witnesses"})
     * @Form\Type("Text")
     * @Form\Validator({"name":"Digits"})
     */
    public $witnesses = null;

    /**
     * @Form\Attributes({"id":""})
     * @Form\Options({
     *     "label": "Date of decision",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y"
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date","options":{"format":"Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $decisionDate = null;

    /**
     * @Form\Attributes({"id":""})
     * @Form\Options({
     *     "label": "Date of notification",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y"
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date","options":{"format":"Y-m-d"}})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $notificationDate = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"js-definition-source chosen-select-large"})
     * @Form\Options({
     *     "label": "Definition",
     *     "disable_inarray_validator": false,
     *     "service_name": "\Olcs\Service\Data\PublicInquiryDefinition",
     *     "use_groups": true,
     *     "empty_option": "Add definition option"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Required(false)
     */
    public $definition = null;

    /**
     * @Form\Attributes({"id":"","class":"extra-long js-definition-target"})
     * @Form\Options({
     *     "label": "Details to be published"
     * })
     * @Form\Required(false)
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":4000}})
     */
    public $decisionNotes = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $case = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $id = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $version = null;
}