<?php

/**
 * Inspection Request Update
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\BusinessService\Service;

use Common\BusinessService\BusinessServiceInterface;
use Common\BusinessService\BusinessServiceAwareInterface;
use Common\BusinessService\BusinessServiceAwareTrait;
use Common\BusinessService\Response;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Common\Service\Entity\InspectionRequestEntityService;
use Common\Service\Data\CategoryDataService;
use Common\Exception\ResourceNotFoundException;

/**
 * Inspection Request Update
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class InspectionRequestUpdate implements
    BusinessServiceInterface,
    ServiceLocatorAwareInterface,
    BusinessServiceAwareInterface
{
    use ServiceLocatorAwareTrait, BusinessServiceAwareTrait;

    /**
     * Process an Inspection Request status update
     *
     * @param array $params
     * @return Common\BusinessService\ResponseInterface
     */
    public function process(array $params)
    {
        $id = $params['id'];
        $statusCode = $params['status'];

        $statuses = [
            'S' => InspectionRequestEntityService::RESULT_TYPE_SATISFACTORY,
            'U' => InspectionRequestEntityService::RESULT_TYPE_UNSATISFACTORY,
        ];

        if (array_key_exists($statusCode, $statuses)) {

            /// update inspection request
            $resultType = $statuses[$statusCode];

            try {
                $updated = $this->getServiceLocator()->get('Entity\InspectionRequest')
                    ->forceUpdate($id, ['resultType' => $resultType]);
            } catch (ResourceNotFoundException $e) {
                return new Response(Response::TYPE_FAILED);
            }

            // create task
            $taskCreated = $this->createTask($id, $resultType);

            return new Response(Response::TYPE_SUCCESS, compact('updated', 'taskCreated'));
        }

        return new Response(Response::TYPE_FAILED);
    }

    /**
     * Create task using business service
     *
     * @param int $id inspection request id
     * @param string $resultType
     * @return boolean success
     */
    protected function createTask($id, $resultType)
    {
        $translator = $this->getServiceLocator()->get('Helper\Translation');

        if ($resultType == InspectionRequestEntityService::RESULT_TYPE_SATISFACTORY) {
            $translateKey = 'inspection-request-task-description-satisfactory';
        } else {
            $translateKey = 'inspection-request-task-description-unsatisfactory';
        }
        $description = $translator->translateReplace($translateKey, [$id]);

        $assignment = $this->getServiceLocator()
            ->get('Processing\Task')
            ->getAssignment(
                [
                    'category' => CategoryDataService::CATEGORY_LICENSING,
                    'subCategory' => CategoryDataService::TASK_SUB_CATEGORY_INSPECTION_REQUEST_SEMINAR,
                ]
            );

        $taskData = array_merge(
            [
                'category' => CategoryDataService::CATEGORY_LICENSING,
                'subCategory' => CategoryDataService::TASK_SUB_CATEGORY_INSPECTION_REQUEST_SEMINAR,
                'description' => $description,
                'isClosed' => 'N',
                'urgent' => 'N',
            ],
            $assignment
        );

        $response = $this->getBusinessServiceManager()->get('Task')->process($taskData);

        return $response->isOk();
    }
}
