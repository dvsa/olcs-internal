<?php

namespace Olcs\Service\Data;

use Common\Service\Data\AbstractData;
use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\ServiceLocatorInterface;
use Common\Service\Data\CloseableInterface;

/**
 * Class PublicInquiry
 * @package Olcs\Service
 */
class Pi extends AbstractData implements CloseableInterface
{
    use CloseableTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $serviceName = 'Pi';

    /**
     * Create PublicInquiry service with injected ref data service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return PublicInquiry
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        parent::createService($serviceLocator);

        return $this;
    }

    /**
     * Fetch PublicInquiry data
     *
     * @param integer|null $id
     * @param array|null $bundle
     * @return array
     */
    public function fetchData($id = null, $bundle = null)
    {
        $id = is_null($id) ? $this->getId() : $id;
        if (is_null($this->getData($id))) {
            $bundle = is_null($bundle) ? $this->getBundle() : $bundle;
            $data =  $this->getRestClient()->get(sprintf('/%d', $id), ['bundle' => json_encode($bundle)]);
            $this->setData($id, $data);
        }

        return $this->getData($id);
    }

    /**
     * Returns the bundle required to get a pi
     * @return array
     */
    public function getBundle()
    {
        $bundle =  [
            'children' => [
                'piStatus' => [],
                'piTypes' => [],
                'reasons' => [
                    /*
                    'children' => [
                        'reason' => []
                    ],
                     */
                ],
                'piHearings' => array(
                    'children' => [
                        'presidingTc' => [],
                        'presidedByRole' => [],
                    ],
                ),
                'writtenOutcome' => array(),
                'decidedByTc' => array(),
                'agreedByTc' => array(),
                'decidedByTcRole' => array(),
                'agreedByTcRole' => array(),
                'decisions' => array(),
                'assignedTo' => array(),
                'case' => array(),
            ]
        ];

        return $bundle;
    }

    /**
     * Can this entity be closed
     * @param $id
     * @return bool
     */
    public function canClose($id)
    {
        $data = $this->fetchData($id);

        if (isset($data['piHearings'][0])) {
            if (!empty($data['piHearings'][0]['cancelledDate'])) {
                return !$this->isClosed($id);
            }
        }

        if (isset($data['writtenOutcome']['id'])) {
            switch($data['writtenOutcome']['id']) {
                case 'piwo_none':
                    return !$this->isClosed($id);
                case 'piwo_reason':
                    if (empty($data['tcWrittenReasonDate']) ||
                        empty($data['writtenReasonLetterDate'])
                    ) {
                        return false;
                    }
                    return !$this->isClosed($id);
                case 'piwo_decision':
                    if (empty($data['tcWrittenDecisionDate']) ||
                        empty($data['decisionLetterSentDate'])
                    ) {
                        return false;
                    }
                    return !$this->isClosed($id);
            }
        }
        return false;
    }

    /**
     * Is this entity closed
     * @param $id
     * @return bool
     */
    public function isClosed($id)
    {
        $data = $this->fetchData($id);

        return (bool) isset($data['closedDate']);
    }

    /**
     * Can this entity be reopened
     * @param $id
     * @return bool
     */
    public function canReopen($id)
    {
        return $this->isClosed($id);
    }
}
