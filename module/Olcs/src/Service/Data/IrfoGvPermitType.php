<?php

namespace Olcs\Service\Data;

use Common\Exception\DataServiceException;
use Common\Service\Data\AbstractDataService;
use Common\Service\Data\ListDataInterface;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoGvPermitTypeList;

/**
 * Class IrfoGvPermitType
 *
 * @package Olcs\Service\Data
 */
class IrfoGvPermitType extends AbstractDataService implements ListDataInterface
{
    public const UNUSED_TYPE_IDS = [5, 8, 12, 20, 15, 19, 16];

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
            $optionData[$datum['id']] = $datum['description'];
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
        if (is_null($this->getData('IrfoGvPermitType'))) {
            $dtoData = IrfoGvPermitTypeList::create([]);
            $response = $this->handleQuery($dtoData);

            if (!$response->isOk()) {
                throw new DataServiceException('unknown-error');
            }

            $this->setData('IrfoGvPermitType', false);

            if (isset($response->getResult()['results'])) {
                $this->setData('IrfoGvPermitType', $this->filterArrayById($response->getResult()['results']));
            }
        }

        return $this->getData('IrfoGvPermitType');
    }

    /**
     * Filter unused IDs out of the array for display (cant remove from DB as 100s of permit records still reference the ID, but not needed for new permits)
     *
     * @param array $data Data
     *
     * @return array
     */
    public function filterArrayById(array $data): array
    {
        return array_filter($data, fn($record) => !in_array($record['id'], self::UNUSED_TYPE_IDS));
    }
}
