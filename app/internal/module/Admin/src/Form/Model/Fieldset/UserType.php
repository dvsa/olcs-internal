<?php

namespace Admin\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Attributes({"id":"userType"})
 * @Form\Type("Zend\Form\Fieldset")
 * @Form\Name("user-type")
 */
class UserType
{
    /**
     * @Form\Options({
     *     "label": "Type",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Common\Service\Data\UserTypesListDataService",
     *     "use_groups": "false"
     * })
     * @Form\Required(true)
     * @Form\Attributes({"id":"userType","placeholder":"", "required":false})
     * @Form\Type("DynamicSelect")
     */
    public $userType = null;

    /**
     * @Form\Attributes({"id":"team","placeholder":"", "required":false})
     * @Form\Options({
     *     "label": "Team",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Common\Service\Data\Team",
     *     "use_groups": "false"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Required(true)
     * @Form\Attributes({"id":"team","placeholder":"", "required":false})
     */
    public $team = null;

    /**
     * @Form\Options({"label":"Application Id"})
     * @Form\Required(true)
     * @Form\Attributes({"class":"medium","id":"application","required":false})
     * @Form\Type("Text")
     */
    public $application = null;

    /**
     * @Form\Options({
     *     "label": "Transport managers",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Common\Service\Data\TransportManager",
     *     "use_groups": "false"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Required(true)
     * @Form\Attributes({"id":"transportManager","placeholder":"", "required":false})
     */
    public $transportManager = null;

    /**
     * @Form\Options({
     *     "label": "Local authority",
     *     "empty_option": "Please Select",
     *     "disable_inarray_validator": false,
     *     "service_name": "Common\Service\Data\LocalAuthority",
     *     "use_groups": "false"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Required(true)
     * @Form\Attributes({"id":"localAuthority","placeholder":"", "required":false})
     */
    public $localAuthority = null;

    /**
     * @Form\Options({"label":"Licence number"})
     * @Form\Required(true)
     * @Form\Attributes({"class":"medium","id":"licenceNumber","required":false})
     * @Form\Type("Text")
     */
    public $licenceNumber = null;

    /**
     * @Form\Options({
     *     "label": "Roles",
     *     "disable_inarray_validator": false,
     *     "help-block": "Use CTRL to select multiple",
     *     "service_name": "Common\Service\Data\Role",
     *     "use_groups": "false"
     * })
     * @Form\Type("DynamicSelect")
     * @Form\Required(true)
     * @Form\Attributes({"id":"roles","placeholder":"","class":"chosen-select-medium","required":false,
     *      "multiple":"multiple"})
     */
    public $roles = null;
}
