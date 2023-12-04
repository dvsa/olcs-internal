<?php

namespace Olcs\Form\Model\Fieldset;

use Laminas\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("main")
 */
class NewMessages
{
    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Category",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Olcs\Service\Data\Category"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $category = null;

        /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Options({
     *     "label": "Application or licence ID",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Olcs\Service\Data\AppLicNo"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $appLicNo = null;

        /**
     * @Form\Attributes({"class":"extra-long","id":"",placeholder:"You can enter up to 1000 characters"})
     * @Form\Options({"label":"Message"})
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Laminas\Filter\StringTrim"})
     * @Form\Validator({"name":"Laminas\Validator\StringLength","options":{"min":5,"max":1000}})
     */
    public $comment = null;

}
