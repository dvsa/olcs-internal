<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Attributes({"class":"table__form"})
 * @Form\Name("details")
 */
class TmResponsibilities
{
    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $id = null;

    /**
     * @Form\Attributes({"value":""})
     * @Form\Type("Hidden")
     */
    public $version = null;

    /**
     * @Form\Options({
     *     "label": "transport-manager.responsibilities.tm-type",
     *     "category": "tm_type",
     *     "fieldset-attributes": {
     *         "class": "checkbox inline"
     *     }
     * })
     * @Form\Type("DynamicRadio")
     * @Form\Required(false)
     * @Form\Validator({
     *      "name":"Zend\Validator\NotEmpty"
     * })
     */
    public $tmType = null;

    /**
     * @Form\Options({
     *     "label": "transport-manager.responsibilities.tm-app-status",
     *     "disable_inarray_validator": false,
     *     "category": "tmap_status"
     * })
     * @Form\Required(true)
     * @Form\Attributes({"id":"","placeholder":"", "required":false})
     * @Form\Type("DynamicSelect")
     */
    public $tmApplicationStatus = null;

    /**
     * @Form\Options({
     *     "label": "transport-manager.responsibilities.is-owner",
     *     "value_options":{
     *         "Y":"Yes",
     *         "N":"No"
     *     },
     *     "fieldset-attributes": {
     *         "class": "checkbox inline"
     *     }
     * })
     * @Form\Type("Radio")
     */
    public $isOwner = null;

    /**
     * @Form\ComposedObject("Common\Form\Model\Fieldset\HoursOfWeek")
     * @Form\Options({
     *     "label": "transport-manager.responsibilities.hours-per-week"
     * })
     */
    public $hoursOfWeek = null;

    /**
     * @Form\Name("otherLicences")
     * @Form\ComposedObject("Common\Form\Model\Fieldset\Table")
     */
    public $otherLicences = null;

    /**
     * @Form\Type("TextArea")
     * @Form\Attributes({
     *      "class":"long"
     * })
     * @Form\Options({
     *     "label": "transport-manager.responsibilities.additional-information",
     *     "label_options": {
     *         "disable_html_escape": "true"
     *     }
     * })
     *
     * @Form\Required(false)
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({
     *      "name":"Zend\Validator\StringLength",
     *      "options":{
     *          "max":4000
     *      }
     * })
     */
    public $additionalInformation;

    /**
     * @Form\Attributes({"id":"file", "class": "file-upload"})
     * @Form\ComposedObject("\Common\Form\Model\Fieldset\MultipleFileUpload")
     */
    public $file = null;
}
