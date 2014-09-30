<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("bus-service-number-and-type")
 */
class BusServiceNumberAndType extends Base
{
    /**
     * @Form\Attributes({"class":"","id":"serviceNo"})
     * @Form\Options({"label":"Service number"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":70}})
     */
    public $serviceNo = null;

    /**
     * @Form\Attributes({"class":"","id":"startPoint"})
     * @Form\Options({"label":"Start point"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":100}})
     */
    public $startPoint = null;

    /**
     * @Form\Attributes({"class":"","id":"finishPoint"})
     * @Form\Options({"label":"Finish point"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":100}})
     */
    public $finishPoint = null;

    /**
     * @Form\Attributes({"class":"","id":"via"})
     * @Form\Options({"label":"Via"})
     * @Form\Required(false)
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":255}})
     */
    public $via = null;

    /**
     * @Form\Attributes({"id":"busServiceTypes","placeholder":"","multiple":"multiple"})
     * @Form\Options({
     *     "label": "Service type",
     *     "disable_inarray_validator": false,
     *     "help-block": "Use CTRL to select multiple",
     *     "service_name": "Olcs\Service\Data\BusServiceType",
     *     "use_groups": "false"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $busServiceTypes = null;

    /**
     * @Form\Attributes({"class":"extra-long","id":"otherDetails"})
     * @Form\Options({"label":"Other N&P details"})
     * @Form\Required(false)
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":5,"max":800}})
     */
    public $otherDetails = null;

    /**
     * @Form\Attributes({"id":"receivedDate"})
     * @Form\Options({
     *     "label": "Date received",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     * @Form\Validator({"name":"Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $receivedDate = null;

    /**
     * @Form\Attributes({"id":"effectiveDate"})
     * @Form\Options({
     *     "label": "Effective date",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     */
    public $effectiveDate = null;

    /**
     * @Form\Attributes({"id":"endDate"})
     * @Form\Options({
     *     "label": "End date",
     *     "create_empty_option": true,
     *     "render_delimiters": false
     * })
     * @Form\Required(false)
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "Date", "options": {"format": "Y-m-d"}})
     */
    public $endDate = null;

    /**
     * @Form\Attributes({"id":"busNoticePeriod","placeholder":"","class":"medium"})
     * @Form\Required(false)
     * @Form\Options({
     *     "label": "Rules",
     *     "service_name": "Olcs\Service\Data\BusNoticePeriod",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select rules",
     * })
     * @Form\Type("DynamicSelect")
     */
    public $busNoticePeriod = null;
}
