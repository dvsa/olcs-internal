<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("main")
 */
class IrhpCandidatePermit extends Base
{
    /**
     * @Form\Type("Select")
     * @Form\Attributes({
     *     "id":"irhpPermitRange",
     *     "class":"medium",
     *     "data-container-class":"js-hidden rangeSelectBox",
     *
     * })
     * @Form\Options({
     *     "label": "Range",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator" : true
     * })
     */
    public $irhpPermitRange;

    /**
     * @Form\Attributes({
     *   "id" : "irhpPermitApplication",
     * })
     *
     * @Form\Type("Zend\Form\Element\Hidden")
     *
     */
    public $irhpPermitApplication;

    /**
     * @Form\Attributes({
     *   "id" : "irhpPermitRangeSelected",
     * })
     *
     * @Form\Type("Zend\Form\Element\Hidden")
     *
     */
    public $irhpPermitRangeSelected = null;
}
