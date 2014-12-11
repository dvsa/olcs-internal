<?php

namespace Admin\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Options({"label":"scanning.details"})
 */
class ScanningDetails
{
    /**
     * @Form\Attributes({"id":"category","placeholder":""})
     * @Form\Options({
     *     "label": "scanning.data.category",
     *     "service_name": "Olcs\Service\Data\Category",
     *     "context": {
     *       "isScanCategory": true
     *     }
     * })
     * @Form\Type("DynamicSelect")
     */
    public $category = null;

    /**
     * @Form\Attributes({"id":"subCategory","placeholder":""})
     * @Form\Options({
     *     "label": "scanning.data.sub_category",
     *     "service_name": "Olcs\Service\Data\SubCategory",
     *     "context": {
     *       "isScan": true
     *     }
     * })
     * @Form\Type("DynamicSelect")
     */
    public $subCategory = null;

    /**
     * @Form\Attributes({"id":"description","placeholder":""})
     * @Form\Options({
     *     "label": "scanning.data.description",
     *     "service_name": "Olcs\Service\Data\SubCategoryDescription",
     *     "context": {}
     * })
     * @Form\Type("DynamicSelect")
     */
    public $description = null;

    /**
     * @Form\Attributes({"id":"other_description","placeholder":""})
     * @Form\Options({
     *     "label": "scanning.data.description"
     * })
     * @Form\Type("Text")
     */
    public $otherDescription = null;

    /**
     * @Form\Attributes({"id":"entity_identifier","placeholder":""})
     * @Form\Options({
     *     "label": "scanning.data.entity"
     * })
     * @Form\Type("Text")
     */
    public $entityIdentifier = null;
}
