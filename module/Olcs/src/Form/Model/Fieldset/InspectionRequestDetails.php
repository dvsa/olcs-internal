<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * Inspection Request Details
 * 
 * @Form\Attributes({"class":"","id":"inspectionRequestDetails"})
 * @Form\Name("inspection-request-details")
 */
class InspectionRequestDetails
{
    /**
     * @Form\Name("dueDate")
     * @Form\Required(true)
     * @Form\Type("Radio")
     * @Form\Options({
     *      "label": "internal.inspection-request.form.due-date",
     *      "value_options":{
     *          "3":"internal.inspection-request.form.3-month",
     *          "6":"internal.inspection-request.form.6-month",
     *          "9":"internal.inspection-request.form.9-month",
     *          "12":"internal.inspection-request.form.12-month"
     *      },
     *      "fieldset-attributes" : {
     *          "class":"inline"
     *      }
     * })
     */
    public $dueDate;

    /**
     * @Form\Name("caseworkerNotes")
     * @Form\Required(false)
     * @Form\Attributes({
     *      "id":"caseworkerNotes",
     *      "class":"long",
     *      "name":"caseworkerNotes",
     *      "required":false
     * })
     * @Form\Options({
     *     "label": "internal.inspection-request.form.caseworker-notes",
     *     "label_attributes": {
     *         "class": "long"
     *     },
     *     "column-size": ""
     * })
     *
     * @Form\Type("TextArea")
     * @Form\Filter({"name":"Zend\Filter\StringTrim"})
     */
    public $caseworkerNotes = null;
}
