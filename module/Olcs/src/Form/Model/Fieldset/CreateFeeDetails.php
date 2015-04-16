<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Attributes({"class":""})
 * @Form\Name("fee-details")
 */
class CreateFeeDetails
{
    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $id = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $version = null;

    /**
     * @Form\Options({
     *     "label": "fees.type",
     *     "short-label": "fees.type",
     *     "label_attributes": {"id": "label-type"},
     *     "empty_option": "Please select",
     *     "service_name":"Olcs\Service\Data\MiscellaneousFeeType"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Validator({"name": "Zend\Validator\NotEmpty"})
     */
    public $feeType = null;

    /**
     * Created date
     *
     * @Form\Options({
     *      "short-label":"fees.created_date",
     *      "label":"fees.created_date",
     *      "label_attributes": {"id": "label-createdDate"}
     * })
     * @Form\Required(true)
     * @Form\Attributes({"required":false})
     * @Form\Type("DateSelect")
     * @Form\Filter({"name": "DateSelectNullifier"})
     * @Form\Validator({"name": "\Common\Form\Elements\Validators\DateNotInFuture"})
     */
    public $createdDate = null;

    /**
     * @Form\Options({
     *      "short-label":"fees.amount",
     *      "label":"fees.amount",
     *      "label_attributes": {"id": "label-amount"}
     * })
     * @Form\Type("Text")
     * @Form\Required(true)
     * @Form\Attributes({"required":false})
     * @Form\AllowEmpty(false)
     * @Form\Input("Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Validator({"name": "Common\Form\Elements\Validators\Money"})
     */
    public $amount = null;
}
