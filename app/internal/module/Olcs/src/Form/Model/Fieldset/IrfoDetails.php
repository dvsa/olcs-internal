<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("main")
 */
class IrfoDetails extends Base
{
    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"idHtml", "required": false})
     * @Form\Options({
     *     "label": "IRFO OP Number",
     * })
     * @Form\Type("Common\Form\Elements\Types\Html")
     */
    public $idHtml = null;

    /**
     * @Form\Attributes({"id":"","placeholder":""})
     * @Form\Required(false)
     * @Form\Options({
     *     "label": "Country of origin",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Common\Service\Data\Country",
     *     "use_groups": false
     * })
     * @Form\Type("DynamicSelect")
     */
    public $irfoNationality = null;
}
