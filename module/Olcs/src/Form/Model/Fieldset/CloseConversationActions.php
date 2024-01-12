<?php

declare(strict_types=1);

namespace Olcs\Form\Model\Fieldset;

use Laminas\Form\Annotation as Form;

/**
 * @codeCoverageIgnore No methods
 * @Form\Name("main")
 */
class CloseConversationActions
{
    /**
     * @Form\Attributes({
     *     "type": "submit",
     *     "data-module": "govuk-button",
     *     "class": "govuk-button govuk-button--warning",
     *     "id": "close"
     * })
     * @Form\Options({
     *     "label": "End and archive conversation"
     * })
     * @Form\Type(\Common\Form\Elements\InputFilters\ActionButton::class)
     */
    public ?ActionButton $close = null;

    /**
     * @Form\Attributes({
     *     "data-module": "govuk-button",
     *     "type": "submit",
     *     "class": "govuk-button govuk-button--secondary",
     *     "id": "cancel"
     * })
     * @Form\Options({
     *     "label": "Cancel"
     * })
     * @Form\Type(\Common\Form\Elements\InputFilters\ActionButton::class)
     */
    public ?ActionButton $cancel = null;
}
