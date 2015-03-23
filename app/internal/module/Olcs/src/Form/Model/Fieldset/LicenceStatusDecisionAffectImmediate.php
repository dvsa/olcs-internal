<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("licence-decision-affect-immediate")
 */
class LicenceStatusDecisionAffectImmediate
{
    /**
     * @Form\Required(true)
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "licence-status.curtailment.immediate-affect",
     *      "value_options":{
     *          "N":"No",
     *          "Y":"Yes"
     *      },
     *      "fieldset-attributes" : {
     *          "class":"inline"
     *      }
     * })
     * @Form\Attributes({
     *     "value": "N"
     * })
     */
    public $immediateAffect;
}