<?php

/**
 * Schedule41Transfer.php
 */
namespace Olcs\Form\Model\Form;

use Laminas\Form\Annotation as Form;

/**
 * @Form\Name("Schedule41OperatingCentres")
 * @Form\Options({"label":""})
 * @Form\Attributes({"method":"post","class":"table__form"})
 */
class Schedule41Transfer
{
    /**
     * @Form\Name("table")
     * @Form\ComposedObject("Common\Form\Model\Fieldset\Table")
     */
    public $table = null;

    /**
     * @Form\Options({
     *      "checked_value":"Y",
     *      "unchecked_value":"N",
     *      "label":"Surrender Licence"
     *  })
     * @Form\Type("OlcsCheckbox")
     */
    public $surrenderLicence = null;

    /**
     * @Form\Attributes({"type":"submit","class":"action--primary large"})
     * @Form\Options({
     *     "label": "Transfer"
     * })
     * @Form\Type("\Common\Form\Elements\InputFilters\ActionButton")
     */
    public $transfer = null;

    /**
     * @Form\Attributes({"type":"submit","class":"action--secondary large", "id": "cancel"})
     * @Form\Options({
     *     "label": "Cancel"
     * })
     * @Form\Type("\Common\Form\Elements\InputFilters\ActionButton")
     */
    public $cancel = null;
}
