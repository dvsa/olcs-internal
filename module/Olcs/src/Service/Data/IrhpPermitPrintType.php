<?php

namespace Olcs\Service\Data;

use Common\Exception\DataServiceException;
use Common\Service\Data\AbstractDataService;
use Common\Service\Data\ListDataInterface;
use Dvsa\Olcs\Transfer\Query\Permits\ReadyToPrintType;

/**
 * Class IrhpPermitPrintType
 *
 * @package Olcs\Service\Data
 */
class IrhpPermitPrintType extends AbstractDataService implements ListDataInterface
{
    /**
     * Format data
     *
     * @param array $data Data
     *
     * @return array
     */
    public function formatData(array $data)
    {
        $optionData = [];

        foreach ($data as $datum) {
            $optionData[$datum['id']] = $datum['name']['description'];
        }

        return $optionData;
    }

    /**
     * Fetch list options
     *
     * @param array|string $context   Context
     * @param bool         $useGroups Use groups
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetchListOptions($context, $useGroups = false)
    {
        $data = $this->fetchListData();

        if (!$data) {
            return [];
        }

        return $this->formatData($data);
    }

    /**
     * Fetch list data
     *
     * @return array
     * @throw DataServiceException
     */
    public function fetchListData()
    {
        if (is_null($this->getData('IrhpPermitPrintType'))) {
            $dtoData = ReadyToPrintType::create([]);
            $response = $this->handleQuery($dtoData);

            if (!$response->isOk()) {
                throw new DataServiceException('unknown-error');
            }

            $this->setData('IrhpPermitPrintType', $response->getResult()['results'] ?? null);
        }

        return $this->getData('IrhpPermitPrintType');
    }
}
