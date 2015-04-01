<?php

namespace Olcs\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("Grant")
 * @Form\Options({"label":""})
 * @Form\Attributes({"method":"post", "class":"js-modal-alert"})
 */
class Grant
{
    /**
     * @Form\Name("messages")
     * @Form\ComposedObject("Common\Form\Model\Fieldset\Messages")
     */
    public $messages;

    /**
     * @Form\Name("form-actions")
     * @Form\Attributes({"class":"actions-container"})
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\GrantFormActions")
     */
    public $formActions = null;
}
