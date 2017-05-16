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
     *     "service_name": "Olcs\Service\Data\SubmissionActionTypes",
     *     "disable_inarray_validator": false,
     *     "use_groups":true
     * })
     * @Form\Type("DynamicSelect")
     */
    public $actionTypes = null;

    /**
     * @Form\Required(true)
     * @Form\Type("DynamicSelect")
     * @Form\Attributes({"id":"","placeholder":"","class":"chosen-select-medium js-sub-legislation",
     * "multiple" : true, "required":false})
     * @Form\Options({
     *     "label": "Legislation",
     *     "service_name": "Olcs\Service\Data\SubmissionLegislation",
     *     "disable_inarray_validator": false,
     *     "use_groups":true
     * })
     * @Form\Filter({"name":"Common\Filter\NullToArray"})
     * @Form\Validator({"name": "NotEmpty", "options": {"array"}})
     * @Form\Validator({"name": "ValidateIfMultiple",
     *      "options":{
     *          "context_field": "actionTypes",
     *          "context_values": {"sub_st_rec_pi", "sub_st_rec_ptr"},
     *          "allow_empty": false,
     *          "validators": {
     *              {"name": "\Zend\Validator\NotEmpty"}
     *          }
     *      }
     * })
     */
    public $reasons = null;

    /**
     * @Form\Attributes({"id":"","class":"extra-long tinymce","name":"comment"})
     * @Form\Options({
     *     "label": "Recommendation reason",
     *     "label_attributes": {
     *         "class": ""
     *     }
     * })
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Filter({"name":"htmlpurifier"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5}})
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
