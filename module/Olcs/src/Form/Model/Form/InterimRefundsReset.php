<?php


namespace Olcs\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("interim-refunds-reset")
 * @Form\Attributes({"method":"post", "class": "filters form__filter"})
 * @Form\Type("Common\Form\Form")
 * @Form\Options({"prefer_form_input_filter": true, "bypass_auth": true})
 */
class InterimRefundsReset
{

    /**
     * @Form\Attributes({"type":"submit","class":"action--primary"})
     * @Form\Options({
     *     "label": "interim.submit.return",
     * })
     * @Form\Type("\Zend\Form\Element\Button")
     */
    public $submit = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $reset = true;
}