<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("bus-reg-quality-fields")
 */
class BusRegQuality extends Base
{
    /**
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "Operate on part of a Quality Partnership Scheme current or future",
     *      "value_options":
     *          {
     *              "N":"No",
     *              "Y":"Yes"
     *          }
     * })
     * @Form\Attributes({
     *      "id":"isQualityPartnership",
     * })
     */
    public $isQualityPartnership;

    /**
     * @Form\Attributes({
     *      "id":"qualityPartnershipDetails",
     *      "class":"extra-long",
     *      "name":"qualityPartnershipDetails"
     * })
     * @Form\Options({
     *     "label": "Local transport authority or lead authority for Quality Partnership Scheme",
     *     "label_attributes": {
     *         "class": "extra-long"
     *     },
     *     "column-size": "",
     *     "help-block": "Local transport authority or lead authority for Quality Partnership Scheme"
     * })
     *
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     *
     * @Form\Type("Textarea")
     * @Form\Required(false)
     *
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     *
     * @Form\Validator({
     *      "name": "Zend\Validator\StringLength",
     *      "options": {
     *          "max":4000
     *      }
     * })
     */
    public $qualityPartnershipDetails;

    /**
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "Will Quality Partnership Scheme facilities be used",
     *      "value_options":
     *          {
     *              "N":"No",
     *              "Y":"Yes"
     *          }
     * })
     * @Form\Attributes({
     *      "id":"qualityPartnershipFacilitiesUsed",
     * })
     */
    public $qualityPartnershipFacilitiesUsed;

    /**
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "Operate on part of a Quality Partnership Scheme current or future",
     *      "value_options":
     *          {
     *              "N":"No",
     *              "Y":"Yes"
     *          }
     * })

     * @Form\Attributes({
     *      "id":"isQualityContract",
     * })
     */
    public $isQualityContract;

    /**
     * @Form\Attributes({
     *      "id":"qualityContractDetails",
     *      "class":"extra-long",
     *      "name":"qualityContractDetails"
     * })
     * @Form\Options({
     *     "label": "Local transport authority or lead authority for Quality Contract Scheme",
     *     "label_attributes": {
     *         "class": "extra-long"
     *     },
     *     "column-size": ""
     * })
     *
     * @Form\Type("Textarea")
     * @Form\Required(false)
     *
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     *
     * @Form\Validator({
     *      "name": "Zend\Validator\StringLength",
     *      "options": {
     *          "max":4000
     *      }
     * })
     */
    public $qualityContractDetails;
}
