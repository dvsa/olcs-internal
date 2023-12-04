<?php

namespace Olcs\Service\Data;

use Common\Exception\DataServiceException;
use Common\Service\Data\AbstractListDataService;
use Dvsa\Olcs\Transfer\Query as TransferQry;

/**
 * Class Category
 *
 * @package Olcs\Service\Data
 */
class Organisation extends AbstractListDataService
{

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
        $data = (array)$this->getData('categories');

        if (0 !== count($data)) {
            return $data;
        }

        $params = array_filter(
            [
                
            ]
        );

        $response = $this->handleQuery(
            TransferQry\Organisation\Dashboard::class
        );

        if (!$response->isOk()) {
            throw new DataServiceException('unknown-error');
        }

        $result = $response->getResult();
        dd($result);

       // $this->setData('categories', (isset($result['results']) ? $result['results'] : null));

        //return $this->getData('categories');
    }

    
}
