<?php

namespace Admin\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;
use Common\Form\Model\Form\Traits\IdTrait;
use Common\Form\Model\Form\Traits\VersionTrait;

/**
 * @codeCoverageIgnore No methods
 * @Form\Attributes({"class":""})
 * @Form\Name("team-details")
 */
class TeamDetails
{
    use VersionTrait,
        IdTrait;

    /**
     * @Form\Attributes({"placeholder":"","class":"medium"})
     * @Form\Options({"label":"Name"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"max":70}})
     */
    public $name = null;

    /**
     * @Form\Attributes({"placeholder":"","class":"medium"})
     * @Form\Options({"label":"Description"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"max":255}})
     */
    public $description = null;

    /**
     * @Form\Attributes({"id":"team","placeholder":"","class":"medium"})
     * @Form\Options({
     *     "label": "Traffic area",
     *     "service_name": "Common\Service\Data\TrafficArea",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "help-block": "Please select a traffic area",
     * })
     * @Form\Required(false)
     * @Form\Type("DynamicSelect")
     */
    public $trafficArea = null;
}
