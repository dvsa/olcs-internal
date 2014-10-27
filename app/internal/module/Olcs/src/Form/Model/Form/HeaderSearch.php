<?php

namespace Olcs\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("search")
 * @Form\Attributes({"method":"post", "action": "/search"})
 * @Form\Hydrator("Zend\Stdlib\Hydrator\ArraySerializable")
 */
class HeaderSearch
{
    /**
     * @Form\Attributes({"class": "search__input", "placeholder": "Search"})
     * @Form\Type("Text")
     * @Form\Validator({"name": "NotEmpty"})
     */
    protected $search;

    /**
     * @Form\Attributes({
     *      "class": "search__select",
     *      "id": "search-select",
     *      "style": "position:absolute; top:3px; right:40px"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Options({
     *      "service_name": "Olcs\Service\Data\Search\Search"
     * })
     */
    protected $index;

    /**
     * @Form\Attributes({"class": "search__button"})
     * @Form\Type("Submit")
     */
    protected $submit;
}
