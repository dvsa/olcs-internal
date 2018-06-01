<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("assignment")
 * @Form\Options({
 *     "label": "tasks.assignment",
 * })
 */
class TaskAssignment
{
    /**
     * @Form\Required(true)
     * @Form\Type("DynamicSelect")
     * @Form\Attributes({"id":"assignedToTeam","placeholder":""})
     * @Form\Options({
     *     "label": "tasks.data.team",
     *     "service_name": "Olcs\Service\Data\Team",
     *     "empty_option": "please-select",
     *     "error-message": "Team and/or owner must be selected",
     * })
     * @Form\Validator({"name": "NotEmpty", "options": {"null"}})
     * @Form\Validator({
     *     "name": "ValidateIf",
     *     "options":{
     *          "context_field": "assignedToUser",
     *          "context_values": {""},
     *          "validators": {
     *              {
     *                  "name":"Zend\Validator\NotEmpty",
     *              },
     *          },
     *      }
     * })
     */
    public $assignedToTeam = null;

    /**
     * @Form\Required(true)
     * @Form\Type("DynamicSelect")
     * @Form\Attributes({"id":"assignedToUser","placeholder":""})
     * @Form\Options({
     *     "label": "tasks.data.owner",
     *     "service_name": "Olcs\Service\Data\UserListInternalTask",
     *     "empty_option": "Unassigned",
     * })
     * @Form\Validator({"name": "NotEmpty", "options": {"null"}})
     */
    public $assignedToUser = null;
}
