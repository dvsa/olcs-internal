<?php

namespace Olcs\Form\Model\Form\Auth;

use Laminas\Form\Annotation as Form;

/**
 * @Form\Name("login-form")
 * @Form\Attributes({"method":"post"})
 */
class Login
{
    /**
     * @Form\Options({
     *     "label": "auth.login.username",
     *     "short-label": "auth.login.username",
     *     "label_attributes": {
     *         "aria-label": "Enter your username"
     *     }
     * })
     * @Form\Attributes({
     *     "id": "auth.login.username"
     * })
     * @Form\Filter({"name": "Laminas\Filter\StringTrim"})
     * @Form\Filter({"name": "Common\Filter\StripSpaces"})
     * @Form\Validator({"name":"Dvsa\Olcs\Transfer\Validators\Username"})
     * @Form\Type("Text")
     */
    public $username = null;

    /**
     * @Form\Options({
     *     "label": "auth.login.password",
     *     "short-label": "auth.login.password",
     *     "label_attributes": {
     *         "aria-label": "Enter your password"
     *     }
     * })
     * @Form\Attributes({
     *     "id": "auth.login.password"
     * })
     * @Form\Filter({"name": "Laminas\Filter\StringTrim"})
     * @Form\Type("Password")
     */
    public $password = null;

    /**
     * @Form\Attributes({"id": "declarationRead", "class": ""})
     * @Form\Options({
     *     "label": "auth.login.declaration-read.label",
     *     "label_attributes" : {
     *         "class":"form-control form-control--checkbox form-control--confirm full-width"
     *     },
     *     "use_hidden_element":false,
     *     "checked_value":"Y",
     *     "unchecked_value":"N",
     *     "must_be_value": "Y",
     *     "error-message" : "auth.login.declaration-read.error"
     * })
     * @Form\Type("\Common\Form\Elements\InputFilters\SingleCheckbox")
     */
    public $declarationRead = null;

    /**
     * @Form\Attributes({
     *     "id": "auth.login.button",
     *     "value": "auth.login.button",
     *     "class": "action--primary large"
     * })
     * @Form\Type("Submit")
     */
    public $submit = null;
}
