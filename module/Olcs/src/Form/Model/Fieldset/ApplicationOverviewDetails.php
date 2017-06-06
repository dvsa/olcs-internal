<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * Application Overview Details fieldset
 */
class ApplicationOverviewDetails
{
    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Lead Traffic Commissioner",
     *     "value_options": {
     *     },
     *     "empty_option": "Not set",
     *     "disable_inarray_validator": false,
     * })
     * @Form\Type("\Zend\Form\Element\Select")
     * @Form\Required(false)
     */
    public $leadTcArea = null;

    /**
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Options({
     *     "label": "Application received date",
     *     "create_empty_option": true,
     *     "render_delimiters": false,
     *     "required": false,
     *     "max_year_delta": "+5",
     *     "min_year_delta": "-5"
     * })
     * @Form\Attributes({"required":false})
     * @Form\Filter({"name": "\Common\Filter\DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name": "Date", "options":{"format":"Y-m-d"}})
     * @Form\Validator({"name":"Zend\Validator\NotEmpty","options":{"null"}})
     */
    public $receivedDate = null;

    /**
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Options({
     *     "label": "Target completion",
     *     "create_empty_option": true,
     *     "render_delimiters": false,
     *     "required": false,
     *     "max_year_delta": "+5",
     *     "min_year_delta": "-5"
     * })
     * @Form\Attributes({"required":false})
     * @Form\Filter({"name": "\Common\Filter\DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name": "Date", "options":{"format":"Y-m-d"}})
     * @Form\Validator({"name":"Zend\Validator\NotEmpty","options":{"null"}})
     */
    public $targetCompletionDate = null;

    /**
     * @Form\Options({"checked_value":"Y","unchecked_value":"N","label":"overview.fieldset.check.welsh"})
     * @Form\Type("OlcsCheckbox")
     */
    public $translateToWelsh = null;

    /**
     * @Form\Options({
     *      "checked_value":"Y",
     *      "unchecked_value":"N",
     *      "label":"overview.fieldset.check.override-opposition-date"
     * })
     * @Form\Type("OlcsCheckbox")
     */
    public $overrideOppositionDate = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $version = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $id = null;
}
