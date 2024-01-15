<?php

namespace Olcs\Form\Model\Fieldset;

use Laminas\Form\Annotation as Form;

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
     * @Form\Validator({"name": "\Laminas\Validator\NotEmpty"})
     * @Form\Validator({"name": "\Common\Validator\Date"})
     * @Form\Validator({"name":"Date","options":{"format":"Y-m-d"}})
     * @Form\Validator("\Common\Form\Elements\Validators\DateNotInFuture")
     */
    public $surrenderDate = null;
}
