<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("licence-decision-revoke")
 */
class LicenceStatusDecisionRevoke
{
    /**
     * @Form\Type("DateTimeSelect")
     * @Form\Filter({"name": "DateTimeSelectNullifier"})
     * @Form\Validator({
     *     "name": "Date",
     *     "options": {
     *         "format": "Y-m-d H:i:s",
     *         "messages": {
     *             "dateInvalidDate": "The input does not appear to be a valid date and time"
     *         }
     *     }
     * })
     * @Form\Required(true)
     * @Form\Options({
     *     "label": "licence-status.revocation.from",
     *      "create_empty_option": true,
     *      "max_year_delta": "+5",
     *      "min_year_delta": "-5",
     * })
     * @Form\Attributes({"required":false})
     */
    public $revokeFrom = null;
}
