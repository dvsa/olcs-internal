<?php

namespace Olcs\Controller\Traits;

use Common\RefData;
use Dvsa\Olcs\Transfer\Query\Permits\ById;
use Dvsa\Olcs\Transfer\Query\Permits\EcmtApplicationByLicence;
use Dvsa\Olcs\Utils\Constants\FilterOptions;
use Zend\Form\Element\Select;

/**
 * Permit Search Trait
 */
trait PermitSearchTrait
{
    /**
     * Get Permits Table Name
     *
     * @return string
     */
    protected abstract function getPermitTableName();

    /**
     * Inspect the request to see if we have any filters set, and if necessary, filter them down to a valid subset
     *
     * @param array $extra Filters data
     *
     * @return array
     */
    protected function mapPermitFilters($extra = [])
    {
        $defaults = [
            'sort' => 'id',
            'order' => 'DESC',
            'page' => 1,
            'limit' => 10,
        ];

        $filters = array_merge(
            $defaults,
            $extra,
            $this->getRequest()->getQuery()->toArray()
        );

        // nuke any empty values
        return array_filter(
            $filters,
            function ($v) {
                return $v === false || !empty($v);
            }
        );
    }

    /**
     * Create filter form
     *
     *
     * @return \Zend\Form\FormInterface
     */
    protected function getPermitForm()
    {
        /** @var \Zend\Di\ServiceLocator $sm */
        $sm = $this->getServiceLocator();

        /** @var \Common\Service\Helper\FormHelperService $formHelper */
        $formHelper = $sm->get('Helper\Form');

        $form = $formHelper->createForm('PermitsHome', false);
        $formHelper->setFormActionFromRequest($form, $this->getRequest());

        return $form;
    }

    /**
     * Create table and populate with data from Api
     *
     * @param array $filters Filters data
     *
     * @return \Common\Service\Table\TableBuilder
     */
    protected function getPermitsTable($filters = [])
    {
        $response = $this->getPermitList($filters);
        $permits = $response->getResult();

        $filters['query'] = $this->getRequest()->getQuery();

        $table = $this->getTable($this->getPermitTableName(), $permits, $filters);
        $this->updateTableActionWithQuery($table);

        return $table;
    }


    /**
     * Get the Permit List
     *
     * @param array $filters Filters
     *
     * @return \Common\Service\Cqrs\Response
     * @throws \Exception
     */
    private function getPermitList($filters)
    {
        $query = array_merge($filters, ['statusIds' => [
            RefData::ECMT_APP_STATUS_NOT_YET_SUBMITTED,
            RefData::ECMT_APP_STATUS_UNDER_CONSIDERATION,
            RefData::ECMT_APP_STATUS_AWAITING_FEE,
            RefData::ECMT_APP_STATUS_WITHDRAWN,
            RefData::ECMT_APP_STATUS_CANCELLED,
            RefData::ECMT_APP_STATUS_UNSUCCESSFUL]
        ]);
        /** @var \Common\Service\Cqrs\Response $response */
        $response = $this->handleQuery(EcmtApplicationByLicence::create($query));
        if (!$response->isOk()) {
            throw new \Exception('Error retrieving permit list');
        }

        return $response;
    }

    /**
     * Update table action with query
     *
     * @param \Common\Service\Table\TableBuilder $table Table
     *
     * @return void
     */
    protected function updateTableActionWithQuery($table)
    {
        $query = $this->getRequest()->getUri()->getQuery();
        if ($query) {
            $action = $table->getVariable('action') . '?' . $query;
            $table->setVariable('action', $action);
        }
    }
}
