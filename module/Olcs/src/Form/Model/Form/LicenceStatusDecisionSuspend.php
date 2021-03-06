<?php

namespace Olcs\Form\Model\Form;

use Laminas\Form\Annotation as Form;

/**
 * @Form\Name("LicenceStatusDecisionSuspend")
 * @Form\Attributes({"method":"post", "class":"status-decision-form"})
 * @Form\Type("Common\Form\Form")
 * @Form\Options({"prefer_form_input_filter": true})
 */
class LicenceStatusDecisionSuspend
{
    /**
     * @Form\Name("licence-decision-affect-immediate")
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\LicenceStatusDecisionAffectImmediate")
     */
    public $affectImmediate = null;

    /**
     * @Form\Name("licence-decision")
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\LicenceStatusDecisionSuspend")
     */
    public $suspend = null;

    /**
     * @Form\Name("licence-decision-legislation")
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\LicenceDecisionLegislation")
     */
    public $licenceDecisionLegislation = null;

    /**
     * @Form\Name("form-actions")
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\LicenceStatusDecisionFormActions")
     */
    public $formActions = null;
}
