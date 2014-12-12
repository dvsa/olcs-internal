<?php

/**
 * Layout
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\View\Model\Application;

use Zend\View\Model\ViewModel;
use Common\View\AbstractViewModel;

/**
 * Layout
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class Layout extends AbstractViewModel
{
    protected $template = 'layout/base';

    protected $terminate = true;

    public function __construct($content, array $params = array())
    {
        $header = new ViewModel($params);
        $header->setTemplate('view-new/partials/application-header.phtml');

        $this->addChild($header, 'header');
        $this->addChild($content, 'content');
    }
}
