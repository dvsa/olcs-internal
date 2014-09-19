<?php

namespace Olcs\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("appeal")
 * @Form\Options({"label":"Appeal"})
 * @Form\Attributes({"method":"post"})
 * @Form\Type("Common\Form\Form")
 * @Form\InputFilter("Common\Form\InputFilter")
 */
class Appeal
{
    /**
     * @Form\Name("fields")
     * @Form\Options({"label":"Appeal Details"})
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\Appeal")
     */
    public $fields = null;

    /**
     * @Form\Name("form-actions")
     * @Form\Attributes({"class":"actions-container"})
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\CancelFormActions")
     */
    public $formActions = null;
}
