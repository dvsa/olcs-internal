<?php

declare(strict_types=1);

namespace Olcs\Form\Model\Form;

use Laminas\Form\Annotation as Form;

/**
 * @codeCoverageIgnore no methods
 * @Form\Name("close_conversation")
 * @Form\Attributes({"method":"post"})
 * @Form\Type("Common\Form\Form")
 * @Form\Options({"prefer_form_input_filter": true})
 */
class CloseConversation
{
    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $id = null;

    /**
     * @Form\Name("form-text")
     * @Form\ComposedObject(\Olcs\Form\Model\Fieldset\CloseConversationText::class)
     */
    public $text = null;

    /**
     * @Form\Name("form-actions")
     * @Form\Attributes({"class":"govuk-button-group"})
     * @Form\ComposedObject(\Olcs\Form\Model\Fieldset\CloseConversationActions::class)
     */
    public $formActions = null;
}
