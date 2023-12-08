<?php

namespace Olcs\Service\Data;

use Common\Exception\DataServiceException;
use Common\Service\Data\AbstractListDataService;
use Dvsa\Olcs\Transfer\Query as TransferQry;

/**
 * Class Organisation
 *
 * @package Olcs\Service\Data
 */
class Organisation extends AbstractListDataService
{
    /** @var  int */
    protected $orgId;

    /**
     * Fetch list data
     *
     * @param array $context Parameters
     *
     * @return array
     * @throw DataServiceException
     */
    public function fetchListData($context = null)
    {
       
        $data = (array)$this->getData('organisation');

        if (0 !== count($data)) {
            return $data;
        }
      
        $params = ['id' => $this->getOrgId()];

        $response = $this->handleQuery(
            TransferQry\Organisation\Dashboard::create($params)
        );

        if (!$response->isOk()) {
            throw new DataServiceException('unknown-response');
        }
        

        $result = $response->getResult();
       
        dd($result);

       $this->setData('organisation', (isset($result['results']) ? $result['results'] : null));

        return $this->getData('organisation');
    }


     /**
     * Get Organisation identifier
     *
     * @return int
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Set Organisation identifier
     *
     * @param int $orgId Organisation id
     *
     * @return $this
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    
}
