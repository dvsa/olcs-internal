<?php

namespace Admin\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;
use Common\Form\Model\Form\Traits\VersionTrait;

/**
 * Financial Standing Fieldset
 */
class FinancialStandingRate
{
    use VersionTrait;

    /**
     * @Form\Name("goodsOrPsv")
     * @Form\Attributes({"id": ""})
     * @Form\Options({
     *      "short-label": "financial-standing-rate-element-goodsOrPsv",
     *      "fieldset-attributes": {
     *          "id": "fieldset-operator-type",
     *          "class": "checkbox"
     *      },
     *      "fieldset-data-group": "operator-type",
     *      "label": "financial-standing-rate-element-goodsOrPsv",
     *      "value_options":{
     *          "lcat_gv":"Goods",
     *          "lcat_psv":"PSV"
     *      }
     * })
     * @Form\Type("Radio")
     */
    public $goodsOrPsv = null;

    /**
     * @Form\Name("licenceType")
     * @Form\Attributes({"id": ""})
     * @Form\Options({
     *      "short-label": "financial-standing-rate-element-licenceType",
     *      "fieldset-attributes": {
     *          "id": "fieldset-licence-type",
     *          "class": "checkbox"
     *      },
     *      "fieldset-data-group": "licence-type",
     *      "label": "financial-standing-rate-element-licenceType",
     *      "value_options":{
     *          "ltyp_r": "Restricted",
     *          "ltyp_sn": "Standard National",
     *          "ltyp_si": "Standard International",
     *          "ltyp_sr": "Special Restricted"
     *      }
     * })
     * @Form\Type("Radio")
     */
    public $licenceType = null;

    /**
     * @Form\Name("firstVehicleRate")
     * @Form\Attributes({"id": ""})
     * @Form\Options({
     *      "short-label": "financial-standing-rate-element-firstVehicleRate-short",
     *      "label": "financial-standing-rate-element-firstVehicleRate",
     *      "label_attributes": {"id": "label-firstVehicleRate"}
     * })
     * @Form\Type("Text")
     * @Form\Validator({"name": "Digits"})
     */
    public $firstVehicleRate = null;

    /**
     * @Form\Name("additionalVehicleRate")
     * @Form\Attributes({"id": ""})
     * @Form\Options({
     *      "short-label": "financial-standing-rate-element-additionalVehicleRate-short",
     *      "label": "financial-standing-rate-element-additionalVehicleRate",
     *      "label_attributes": {"id": "label-additionalVehicleRate"}
     * })
     * @Form\Type("Text")
     * @Form\Validator({"name": "Digits"})
     */
    public $additionalVehicleRate = null;

    /**
     * @Form\Name("effectiveFrom")
     * @Form\Attributes({"id": ""})
     * @Form\Options({
     *      "short-label": "financial-standing-rate-element-effectiveDate",
     *      "label": "financial-standing-rate-element-effectiveDate",
     *      "label_attributes": {"id": "label-effectiveFrom"},
     *      "render_delimiters": false,
     *      "create_empty_option": true,
     *      "required": true,
     *      "max_year_delta": "+10",
     *      "min_year_delta": "-10"
     * })
     * @Form\Type("\Common\Form\Elements\Custom\DateSelect")
     */
    public $effectiveFrom = null;
}
