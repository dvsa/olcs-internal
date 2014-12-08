<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("bus-service-number-and-type")
 */
class BusRegisterServiceTimetable
{
    /**
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "Timetable acceptable",
     *      "value_options":{
     *          "N":"No",
     *          "Y":"Yes"
     *      },
     *      "fieldset-attributes" : {
     *          "class":"inline"
     *      }
     * })
     * @Form\Attributes({
     *      "id":"timetable[timetableAcceptable]",
     *      "value":"N"
     * })
     */
    public $timetableAcceptable;

    /**
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "Maps supplied",
     *      "value_options":{
     *          "N":"No",
     *          "Y":"Yes"
     *      },
     *      "fieldset-attributes" : {
     *          "class":"inline"
     *      }
     * })
     * @Form\Attributes({
     *      "id":"mapSupplied",
     *      "value":"N"
     * })
     */
    public $mapSupplied;

    /**
     * @Form\Attributes({"class":"extra-long","id":"timetable[routeDescription]"})
     * @Form\Options({"label":"Route description"})
     * @Form\Required(false)
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     * @Form\Validator({"name":"Zend\Validator\StringLength","options":{"max":800}})
     */
    public $routeDescription = null;
}
