<?php

namespace Olcs\Form\Model\Fieldset;

use Laminas\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("main")
 */
class Conversation
{
     /**
     * @Form\Attributes({"id":"category","placeholder":""})
     * @Form\Options({
     *     "label": "tasks.data.category",
     *     "service_name": "Olcs\Service\Data\Category",
     *     "context": {"isMessagingCategory": "Y" },
     *     "empty_option": "Please Select"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $category = null;

    /**
     * @Form\Attributes({"id":"subCategory","placeholder":""})
     * @Form\Options({
     *     "label": "tasks.data.sub_category",
     *     "service_name": "Olcs\Service\Data\SubCategory",
     *     "context": {"isMessagingCategory": "Y" },
     *     "empty_option": "Please Select"
     *
     * })
     * @Form\Type("DynamicSelect")
     */
    public $subCategory = null;


     /**
     * @Form\Attributes({"id":"appLicNo","placeholder":""})
     * @Form\Options({
     *     "label": "Application or licence ID",
     *     "empty_option": "Please Select",
     * })
     * @Form\Type("Select")
     */
    public $appLicNo = null;

    /**
     * @Form\Attributes({"class":"extra-long","id":""})
     * @Form\Options({"label":"Message"})
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Laminas\Filter\StringTrim"})
     * @Form\Validator({"name":"Laminas\Validator\StringLength","options":{"min":5,"max":1000}})
     */
    public $comment = null;

}
