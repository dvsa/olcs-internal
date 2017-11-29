<?php

namespace Admin\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("PublishedPublicationFilter")
 * @Form\Attributes({"method":"get","class":"filters form__filter"})
 * @Form\Type("Common\Form\Form")
 * @Form\Options({"prefer_form_input_filter": true})
 */
class PublishedPublicationFilter
{
    /**
     * @Form\Type("Select")
     * @Form\Required(false)
     * @Form\Options({
     *      "label": "Publication type",
     *      "empty_option": "All",
     *      "value_options": {
     *          "A&D":"Applications and Decisions",
     *          "N&P":"Notices and Proceedings"
     *      }
     * })
     */
    public $pubType;

    /**
     * @Form\Options({
     *     "label": "Publication Date",
     *     "default_date": "now"
     * })
     * @Form\Type("MonthSelect")
     */
    public $pubDate;

    /**
     * @Form\Options({
     *     "label": "Traffic area",
     *     "service_name": "Common\Service\Data\TrafficArea",
     *     "empty_option": "All",
     * })
     * @Form\Type("DynamicSelect")
     */
    public $trafficArea;

    /**
     * @Form\Attributes({"type":"submit","class":"action--primary"})
     * @Form\Options({"label": "filter-button"})
     * @Form\Type("\Zend\Form\Element\Button")
     */
    public $filter = null;
}
