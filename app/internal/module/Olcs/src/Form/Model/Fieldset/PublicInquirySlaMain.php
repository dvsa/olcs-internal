<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("main")
 * @Form\Options({"label":""})
 */
class PublicInquirySlaMain extends CaseBase
{
    /**
     * @Form\Options({
     *     "label": "Call up letter issued",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y",
     *     "hint": "some hint",
     *     "category": "pi",
     *     "field": "callUpLetterDate"
     * })
     * @Form\Required(false)
     * @Form\Type("SlaDateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $callUpLetterDate = null;

    /**
     * @Form\Options({
     *     "label": "Brief to TC",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y",
     *     "category": "pi",
     *     "field": "briefToTcDate"
     * })
     * @Form\Required(false)
     * @Form\Type("SlaDateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $briefToTcDate = null;

    /**
     * @Form\Attributes({"id":"","placeholder":"","class":"medium"})
     * @Form\Options({
     *     "label": "Written outcome",
     *     "category": "pi_written_outcome",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a category"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Required(false)
     */
    public $writtenOutcome = null;

    /**
     * @Form\Options({
     *     "label": "Date of TC's written decision",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y",
     *     "category": "pi",
     *     "field": "tcWrittenDecisionDate"
     * })
     * @Form\Required(true)
     * @Form\AllowEmpty(true)
     * @Form\Input("\Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Type("SlaDateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({
     *      "name": "ValidateIf",
     *      "options": {
     *          "context_field": "writtenOutcome",
     *          "context_values": {"piwo_decision"},
     *          "allow_empty": true,
     *          "validators": {
     *              {"name": "Date", "options": {"format": "Y-m-d"}},
     *              {"name": "\Common\Form\Elements\Validators\DateNotInFuture"}
     *          }
     *      }
     * })
     */
    public $tcWrittenDecisionDate = null;

    /**
     * @Form\Options({
     *     "label": "Decision letter sent",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y",
     *     "category": "pi",
     *     "field": "decisionLetterSentDate"
     * })
     * @Form\Required(true)
     * @Form\AllowEmpty(true)
     * @Form\Input("\Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Type("SlaDateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({
     *      "name": "ValidateIf",
     *      "options": {
     *          "context_field": "writtenOutcome",
     *          "context_values": {"piwo_decision"},
     *          "allow_empty": true,
     *          "validators": {
     *              {"name": "Date", "options": {"format": "Y-m-d"}},
     *              {"name": "\Common\Form\Elements\Validators\DateNotInFuture"}
     *          }
     *      }
     * })
     */
    public $decisionLetterSentDate = null;

    /**
     * @Form\Options({
     *     "label": "Date of TC's written reason",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y",
     *     "category": "pi",
     *     "field": "tcWrittenReasonDate"
     * })
     * @Form\Required(true)
     * @Form\AllowEmpty(true)
     * @Form\Input("\Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Type("SlaDateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({
     *      "name": "ValidateIf",
     *      "options": {
     *          "context_field": "writtenOutcome",
     *          "context_values": {"piwo_reason"},
     *          "allow_empty": true,
     *          "validators": {
     *              {"name": "Date", "options": {"format": "Y-m-d"}},
     *              {"name": "\Common\Form\Elements\Validators\DateNotInFuture"}
     *          }
     *      }
     * })
     */
    public $tcWrittenReasonDate = null;

    /**
     * @Form\Options({
     *     "label": "Written reason letter sent",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y",
     *     "category": "pi",
     *     "field": "writtenReasonLetterDate"
     * })
     * @Form\Required(true)
     * @Form\AllowEmpty(true)
     * @Form\Input("\Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Type("SlaDateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({
     *      "name": "ValidateIf",
     *      "options": {
     *          "context_field": "writtenOutcome",
     *          "context_values": {"piwo_reason"},
     *          "allow_empty": true,
     *          "validators": {
     *              {"name": "Date", "options": {"format": "Y-m-d"}},
     *              {"name": "\Common\Form\Elements\Validators\DateNotInFuture"}
     *          }
     *      }
     * })
     */
    public $writtenReasonLetterDate = null;

    /**
     * @Form\Options({
     *     "label": "Decision letter sent after written decision date",
     *     "create_empty_option": true,
     *     "render_delimiters": "d m y",
     *     "category": "pi",
     *     "field": "decSentAfterWrittenDecDate"
     * })
     * @Form\Required(false)
     * @Form\Type("SlaDateSelect")
     * @Form\Filter({"name":"DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $decSentAfterWrittenDecDate = null;
}
