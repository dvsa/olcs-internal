<?php

/**
 * Appeal Test Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */

namespace OlcsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Olcs\Controller\Cases\Hearing\StayController;
use OlcsTest\Controller\ControllerTestAbstract;

/**
 * Appeal Test Controller
 */
class AppealControllerTest extends ControllerTestAbstract
{
    protected $testClass = 'Olcs\Controller\Cases\Hearing\AppealController';
}