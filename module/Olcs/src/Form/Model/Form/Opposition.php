<?php

namespace Olcs\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("Opposition")
 * @Form\Attributes({"method":"post"})
 * @Form\Type("Common\Form\Form")
 * @Form\Options({"prefer_form_input_filter": true, "label":"Opposition", "action_lcfirst": false})
 */
class Opposition
{
    /**
     * @Form\Name("fields")
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\OppositionFields")
     */
    public $fields = null;


    /**
     * @Form\Name("base")
     * @Form\Attributes({"class":"base"})
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\CaseBase")
     */
    public $caseBase = null;

    /**
     * @Form\Name("form-actions")
     * @Form\Attributes({"class":"actions-container"})
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\CancelFormActions")
     */
    public $formActions = null;
}
