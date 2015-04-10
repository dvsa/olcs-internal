<?php
namespace Olcs\Controller\Traits;

use Zend\View\Model\ViewModel;

/**
 * Class DeleteActionTrait
 * @package Olcs\Controller
 */
trait DeleteActionTrait
{

    abstract protected function redirectToIndex();
    /**
     * Performs a delete action and redirects to the index
     */
    public function deleteAction()
    {
        $identifierName = $this->getIdentifierName();
        $id = $this->params()->fromRoute($identifierName);

        $response = $this->confirm(
            'Are you sure you want to permanently delete the selected record(s)?'
        );

        $title = isset($this->deleteModalTitle) ? $this->deleteModalTitle : 'internal.delete-action-trait.title';
        if ($response instanceof ViewModel) {
            return $this->renderView($response, $title);
        }

        $this->makeRestCall($this->getDeleteServiceName(), 'DELETE', ['id' => $id]);

        $this->addErrorMessage('Deleted successfully');

        return $this->redirectToIndex();
    }

    /**
     * Should return the name of the service to call for deleting the item
     *
     * @return string
     * @throws \LogicException
     */
    public function getDeleteServiceName()
    {
        if (method_exists($this, 'getService')) {
            return $this->getService();
        }

        throw \LogicException('getDeleteServiceName or getService methods were not implemented.');
    }

    /**
     * Retrieve the route match/query parameter name containing the identifier
     *
     * @return string
     */
    public function getIdentifierName()
    {
        if (property_exists($this, 'identifierName')) {
            return $this->identifierName;
        }

        return 'id';
    }
}
