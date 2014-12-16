<?php

namespace Olcs\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore no methods
 * @Form\Name("assignment")
 * @Form\Options({
 *     "label": "tasks.assigment",
 * })
 */
class TaskAssignment
{
    /**
     * @Form\Attributes({"id":"assignedToTeam","placeholder":""})
     * @Form\Options({
     *     "label": "tasks.data.team",
     *     "service_name": "Olcs\Service\Data\Team",
     *     "empty_option": "Please select"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $assignedToTeam = null;

    /**
     * @Form\Attributes({"id":"assignedToUser","placeholder":""})
     * @Form\Options({
     *     "label": "tasks.data.owner",
     *     "service_name": "Olcs\Service\Data\User",
     *     "empty_option": "Unassigned"
     * })
     * @Form\Type("DynamicSelect")
     */
    public $assignedToUser = null;
}
