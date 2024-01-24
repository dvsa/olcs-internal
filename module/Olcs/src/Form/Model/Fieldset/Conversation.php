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
     * @Form\Attributes({"id":"subject","placeholder":""})
     * @Form\Options({
     *     "label": "messaging.create-conversation.subject",
     *     "service_name": "Olcs\Service\Data\MessagingSubject",
     *     "context": {"isMessagingCategory": "Y" },
     *     "empty_option": "Please Select"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $subject = null;

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
