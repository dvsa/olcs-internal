<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("licence-decision-surrender")
 */
class LicenceStatusDecisionSurrender
{
    /**
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Required(true)
     * @Form\Attributes({"required":false})
     * @Form\Options({
     *     "label": "licence-status.surrender.date.label",
     *      "create_empty_option": true,
     *      "min_year_delta": "-5",
     * })
     * @Form\Validator({"name": "\Zend\Validator\NotEmpty"})
     * @Form\Validator({"name":"Date", "options":{"format":"Y-m-d"}})
     * @Form\Validator({"name":"\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $surrenderDate = null;

    /**
     * @Form\Name("form-actions")
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\ConfirmFormActions")
     */
    public $formActions = null;
}
