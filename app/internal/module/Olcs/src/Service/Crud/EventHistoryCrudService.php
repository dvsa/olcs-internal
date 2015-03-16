<?php

/**
 * Event History Crud Service
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\Service\Crud;

use Zend\Form\Form as ZendForm;
use Common\Service\Crud\AbstractCrudService;
use Common\Service\Crud\GenericProcessFormInterface;
use Common\Crud\RetrieveInterface;
use Common\Service\Table\TableBuilder;

/**
 * Event History Crud Service
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class EventHistoryCrudService extends AbstractCrudService implements
    RetrieveInterface
{
    /**
     * Get's one single record.
     *
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        throw new \LogicException('There is no implementation for a single record in Event History.');
    }

    /**
     * Gets a list of records matching criteria.
     *
     * @param array $criteria Search / request criteria.
     *
     * @return array|null
     */
    public function getList(array $criteria = null)
    {
        /** @var \Common\Service\Data\Generic $ds */
        $ds = $this->getServiceLocator()->get('DataServiceManager')->get('Generic\Service\Data\EventHistory');

        return ['Results' => $ds->fetchList($criteria), 'Count' => $ds->getData('total')];
    }

    /**
     * Handle an individual deletion
     *
     * @param int $id
     */
    protected function delete($id)
    {
        //
    }
}
