<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("change-details")
 */
class ApplicationChangeOfEntityDetails
{
    /**
     * @Form\Type("Text")
     * @Form\Required(true)
     * @Form\Attributes({"required":false})
     * @Form\Options({"label":"application.change-of-entity.details.old-licence-no"})
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     */
    public $oldLicenceNo = null;

    /**
     * @Form\Type("Text")
     * @Form\Required(true)
     * @Form\Attributes({"required":false})
     * @Form\Options({"label":"application.change-of-entity.details.previous-operator-name"})
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     */
    public $oldOrganisationName = null;
}
