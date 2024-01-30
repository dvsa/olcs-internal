<?php

declare(strict_types=1);

namespace Olcs\Form\Model\Fieldset;

use Common\Form\Elements\Types\PlainText;
use Laminas\Form\Annotation as Form;

/**
 * @codeCoverageIgnore No methods
 */
class DisableConversationsPopupText
{
    /**
     * @Form\Type(\Common\Form\Elements\Types\PlainText::class)
     * @Form\Attributes({
     *     "value": "Messaging will be disabled for thi user and all active conversations will be archived."
     * })
     */
    public ?PlainText $text = null;
}
