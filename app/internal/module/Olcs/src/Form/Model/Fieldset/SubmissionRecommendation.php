<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("SubmissionRecommendation-fields")
 */
class SubmissionRecommendation extends Base
{
    /**
     * @Form\Attributes({"id":"","placeholder":"", "class":"chosen-select-medium js-sub_st_rec", "multiple":true})
     * @Form\Options({
     *     "label": "Recommendation type",
     *     "category": "sub_st_rec",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     * })
     * @Form\Type("DynamicSelect")
     */
    public $submissionActionStatus = null;

    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"","placeholder":"","class":"chosen-select-medium js-sub-legislation",
     * "multiple" : true, "required":false})
     * @Form\Options({
     *     "label": "Legislation",
     *     "service_name": "Olcs\Service\Data\SubmissionLegislation",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category",
     *     "use_groups":true
     * })
     * @Form\AllowEmpty(true)
     * @Form\Input("Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Type("DynamicSelect")
     */
    public $reasons = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Send to",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Olcs\Service\Data\User"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $recipientUser = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $senderUser = null;

    /**
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "Urgent?",
     *      "value_options":{
     *          "N":"No",
     *          "Y":"Yes"
     *      },
     *      "fieldset-attributes" : {
     *          "class":"inline"
     *      }
     * })
     * @Form\Attributes({"value": "N"})
     */
    public $urgent;

    /**
     * @Form\Attributes({"id":"comment","class":"extra-long","name":"comment"})
     * @Form\Options({
     *     "label": "Reason",
     *     "label_attributes": {
     *         "class": ""
     *     },
     *     "column-size": "",
     * })
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":10000}})
     */
    public $comment = null;

    /**
     * @Form\Attributes({"value":"N"})
     * @Form\Options({"value": "N"})
     * @Form\Type("Hidden")
     */
    public $isDecision = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $submission = null;
}
