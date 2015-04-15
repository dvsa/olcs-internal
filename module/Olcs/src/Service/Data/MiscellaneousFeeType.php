<?php
namespace Olcs\Service\Data;

use Common\Service\Data\ListDataInterface;
use Common\Service\Data\AbstractData;
use Common\Service\Data\ListDataTrait;

/**
 * Miscellaneous Fee Type data service
 *
 */
class MiscellaneousFeeType extends AbstractData implements ListDataInterface
{
    use ListDataTrait;

    /**
     * @var string
     */
    protected $serviceName = 'FeeType';

    public function fetchListData($category = null)
    {
        $params['sort'] = 'description';
        $params['limit'] = 'all';

        if (is_null($this->getData('miscfeetypes'))) {
            $data = $this->getRestClient()->get(['isMiscellaneous' => true], $params);
            $this->setData('miscfeetypes', false);
            if (isset($data['Results'])) {
                $this->setData('miscfeetypes', $data['Results']);
            }
        }

        return $this->getData('miscfeetypes');
    }
}
