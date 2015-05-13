<?php

/**
 * Companies House Initial Data Load
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Cli\Service\Queue\Consumer\CompaniesHouse;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Common\BusinessService\Response;
use Cli\Service\Queue\Consumer\MessageConsumerInterface;

/**
 * Companies House Initial Data Load
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class InitialDataLoad implements MessageConsumerInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Process the message item
     *
     * @param array $item
     * @return boolean
     */
    public function processMessage(array $item)
    {
        $options = (array) json_decode($item['options']);

        $response = $this->getServiceLocator()->get('BusinessServiceManager')
            ->get('Cli\CompaniesHouseLoad')
            ->process(['companyNumber' => $options['companyNumber']]);

        if (!$response->isOk()) {
            return $this->failed($item, $response->getMessage());
        }

        return $this->success($item, $response->getMessage());
    }

    /**
     * Called when processing the message was successful
     *
     * @param array $item
     * @return string
     */
    protected function success(array $item, $message = null)
    {
        $this->getServiceLocator()->get('Entity\Queue')->complete($item);

        return 'Successfully processed message: '
            . $item['id'] . ' ' . $item['options']
            . ' ' . $message;
    }

    /**
     * Mark the message as failed
     *
     * @param array $item
     * @param string $reason
     * @return string
     */
    protected function failed(array $item, $reason = null)
    {
        $this->getServiceLocator()->get('Entity\Queue')->failed($item);

        return 'Failed to process message: '
            . $item['id'] . ' ' . $item['options']
            . ' ' .  $reason;
    }
}
