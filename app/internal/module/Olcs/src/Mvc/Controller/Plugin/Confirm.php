<?php
namespace Olcs\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;

/**
 * Class Confirm - Generates validates and processes the confirm form
 *
 * @package Olcs\Mvc\Controller\Plugin
 */
class Confirm extends AbstractPlugin
{
    public function __invoke($label, $setTerminal = false, $custom = '')
    {
        $form = $this->getController()->getForm('Confirm');

        // we need it for multiple delete in non-modal environment
        $query = $this->getController()->params()->fromQuery();
        if ($query) {
            $form->setAttribute(
                'action',
                $form->getAttribute('action') . '?' . http_build_query($query)
            );
        }

        $post = $this->getController()->params()->fromPost();
        if ($this->getController()->getRequest()->isPost() && isset($post['form-actions']['confirm'])) {
            $form->setData($post);
            if ($form->isValid()) {
                return true;
            }
        }

        if ($custom) {
            $form->get('custom')->setValue($custom);
        }
        $view = new ViewModel();

        $view->setVariable('form', $form);
        $view->setVariable('label', $label);
        if ($setTerminal) {
            $view->setTerminal(true);
        }
        $view->setTemplate('pages/confirm');

        return $view;
    }
}
