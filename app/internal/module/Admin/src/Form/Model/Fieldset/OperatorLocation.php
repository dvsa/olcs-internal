<?php

namespace Admin\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Type("Zend\Form\Fieldset")
 */
class OperatorLocation
{
    /**
     * @Form\Name("niFlag")
     * @Form\Attributes({"id": ""})
     * @Form\Options({
     *      "short-label": "short-label-tol-operator-location",
     *      "fieldset-attributes": {
     *          "id": "operator-location",
     *          "class": "checkbox"
     *      },
     *      "fieldset-data-group": "operator-location",
     *      "label": "application_type-of-licence_operator-location.data.niFlag",
     *      "value_options":{
     *          "N":"Great Britain",
     *          "Y":"Northern Ireland"
     *      }
     * })
     * @Form\Type("Radio")
     */
    public $niFlag = null;
}
