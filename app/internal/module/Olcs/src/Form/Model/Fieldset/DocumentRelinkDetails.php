<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore No methods
 * @Form\Attributes({"class":""})
 * @Form\Name("document-relink-details")
 */
class DocumentRelinkDetails
{
    /**
     * @Form\Required(false)
     * @Form\Attributes({"id":"idHtml", "required": false, "value":"<b>Relink to:</b>"})
     * @Form\Type("Common\Form\Elements\Types\Html")
     */
    public $relinkTo = null;

    /**
     * @Form\Attributes({"id":"relinkType","placeholder":""})
     * @Form\Options({
     *     "label": "Entity",
     *     "disable_empty_option": false,
     *     "disable_inarray_validator": false,
     *      "value_options":{
     *          "application":"Application",
     *          "busReg":"Bus Registration",
     *          "case":"Case",
     *          "licence":"Licence",
     *          "irfoOrganisation":"IRFO",
     *          "transportManager":"Transport Manager"
     *      },
     * })
     * @Form\Type("Zend\Form\Element\Select")
     */
    public $type = null;

    /**
     * @Form\Attributes({"id":"targetId","class":"medium","name":"targetId"})
     * @Form\Options({"label":"Application ID"})
     * @Form\Type("Text")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"min":1,"max":255}})
     */
    public $targetId = null;

    /**
     * @Form\Type("Hidden")
     */
    public $ids = null;
}
