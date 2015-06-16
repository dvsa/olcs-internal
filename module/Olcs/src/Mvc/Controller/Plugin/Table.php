<?php

namespace Olcs\Mvc\Controller\Plugin;

use Common\Service\Table\TableBuilder;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class Table
 * @package Olcs\Mvc\Controller\Plugin
 */
class Table extends AbstractPlugin
{
    /**
     * @var TableBuilder
     */
    private $tableBuilder;

    /**
     * @param TableBuilder $tableBuilder
     */
    public function __construct(TableBuilder $tableBuilder)
    {
        $this->tableBuilder = $tableBuilder;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param $tableName
     * @param $data
     * @param $params
     * @return string
     */
    public function buildTable($tableName, $data, $params)
    {
        $params['query'] = $this->getController()->getRequest()->getQuery();

        return $this->tableBuilder->buildTable($tableName, $data, $params, false);
    }

    /**
     * @return \Zend\Mvc\Controller\AbstractActionController
     */
    public function getController()
    {
        return parent::getController();
    }
}
