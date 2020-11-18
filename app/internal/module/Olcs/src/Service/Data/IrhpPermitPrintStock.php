<?php

namespace Olcs\Service\Data;

use Common\Exception\DataServiceException;
use Common\Service\Data\AbstractDataService;
use Common\Service\Data\ListDataInterface;
use Common\Service\Helper\TranslationHelperService;
use Dvsa\Olcs\Transfer\Query\Permits\ReadyToPrintStock;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IrhpPermitPrintStock
 *
 * @package Olcs\Service\Data
 */
class IrhpPermitPrintStock extends AbstractDataService implements FactoryInterface, ListDataInterface
{
    const COUNTRY_ID_MOROCCO = 'MA';

    /**
     * @var TranslationHelperService
     */
    private $translator;

    /**
     * @var int
     */
    private $irhpPermitType;

    /**
     * Create the service
     *
     * @param ServiceLocatorInterface $serviceLocator Service locator
     *
     * @return IrhpPermitPrintStock
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->translator = $serviceLocator->get('Helper\Translation');

        return $this;
    }

    /**
     * @var string
     */
    private $country;

    /**
     * Set Irhp Permit Type
     *
     * @param int $irhpPermitType Irhp Permit Type
     *
     * @return $this
     */
    public function setIrhpPermitType($irhpPermitType)
    {
        $this->irhpPermitType = $irhpPermitType;
        return $this;
    }

    /**
     * Get Irhp Permit Type
     *
     * @return int
     */
    public function getIrhpPermitType()
    {
        return $this->irhpPermitType;
    }

    /**
     * Set country
     *
     * @param string $country Country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

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
            $optionData[$datum['id']] = $this->generateLabel($datum);
        }

        return $optionData;
    }

    /**
     * Generate a label for an item in the returned data
     *
     * @param array $datum
     *
     * @return string
     */
    private function generateLabel(array $datum)
    {
        if ($this->getCountry() == self::COUNTRY_ID_MOROCCO) {
            return $this->translator->translate($datum['periodNameKey']);
        }

        return (!empty($datum['validFrom']) && !empty($datum['validTo']))
            ? sprintf('%s to %s', $datum['validFrom'], $datum['validTo'])
            : sprintf('Stock %s', $datum['id']);
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
    public function fetchListOptions($context = null, $useGroups = false)
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
        if (is_null($this->getData('IrhpPermitPrintStock'))) {
            $dtoData = ReadyToPrintStock::create(
                [
                    'irhpPermitType' => $this->irhpPermitType,
                    'country' => $this->country,
                ]
            );
            $response = $this->handleQuery($dtoData);

            if (!$response->isOk()) {
                throw new DataServiceException('unknown-error');
            }

            $this->setData('IrhpPermitPrintStock', $response->getResult()['results']);
        }

        return $this->getData('IrhpPermitPrintStock');
    }
}
