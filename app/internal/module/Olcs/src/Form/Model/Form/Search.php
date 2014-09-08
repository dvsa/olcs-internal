<?php

namespace Olcs\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("search")
 * @Form\Attributes({"method":"post"})
 * @Form\Type("Common\Form\Form")
 */
class Search
{
    /**
     * @Form\Attributes({"type":"submit","class":"action--primary large"})
     * @Form\Options({
     *     "label": "Search",
     *     "label_attributes": {
     *         "class": "col-sm-2"
     *     },
     *     "column-size": "sm-10"
     * })
     * @Form\Type("\Zend\Form\Element\Button")
     */
    public $advanced = null;

    /**
     * @Form\Name("search")
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\Search")
     */
    public $search = null;

    /**
     * @Form\Name("search-advanced")
     * @Form\Options({"label":"Advanced search","class":"extra-long"})
     * @Form\ComposedObject("Olcs\Form\Model\Fieldset\SearchAdvanced")
     */
    public $searchAdvanced = null;
}
