<?php

namespace Olcs\Listener\RouteParam;

use Interop\Container\ContainerInterface;
use Common\Exception\DataServiceException;
use Common\RefData;
use Common\Service\Data\Surrender;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;

/**
 * Class Licence
 *
 * @package Olcs\Listener\RouteParam
 */
class Licence implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait;
    use ViewHelperManagerAwareTrait;

    private $annotationBuilderService;
    private $queryService;

    /**
     * @var \Olcs\Service\Marker\MarkerService
     */
    protected $markerService;

    /**
     * @var \Common\Service\Data\Licence
     */
    protected $licenceService;


    /**
     * @var Surrender
     */
    protected $surrenderService;

    /**
     * @var \Laminas\Navigation\Navigation
     */
    protected $navigationService;

    /**
     * @var \Laminas\Navigation\Navigation
     */
    protected $mainNavigationService;

    /**
     * @return \Olcs\Service\Marker\MarkerService
     */
    public function getMarkerService()
    {
        return $this->markerService;
    }

    public function setMarkerService(\Olcs\Service\Marker\MarkerService $markerService)
    {
        $this->markerService = $markerService;
        return $this;
    }


    /**
     * @param Surrender $surrender
     *
     * @return $this
     */
    public function setSurrenderService(Surrender $surrender)
    {
        $this->surrenderService = $surrender;
        return $this;
    }

    /**
     * @param \Common\Service\Data\Licence $licenceService
     *
     * @return $this
     */
    public function setLicenceService($licenceService)
    {
        $this->licenceService = $licenceService;
        return $this;
    }

    /**
     * @return \Common\Service\Data\Licence
     */
    public function getLicenceService()
    {
        return $this->licenceService;
    }

    /**
     * @param \Laminas\Navigation\Navigation $navigationService
     *
     * @return $this
     */
    public function setNavigationService($navigationService)
    {
        $this->navigationService = $navigationService;
        return $this;
    }

    /**
     * @return \Laminas\Navigation\Navigation
     */
    public function getNavigationService()
    {
        return $this->navigationService;
    }

    /**
     * @param \Laminas\Navigation\Navigation $navigationService
     *
     * @return $this
     */
    public function setMainNavigationService($navigationService)
    {
        $this->mainNavigationService = $navigationService;
        return $this;
    }

    /**
     * @return \Laminas\Navigation\Navigation
     */
    public function getMainNavigationService()
    {
        return $this->mainNavigationService;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            RouteParams::EVENT_PARAM . 'licence',
            array($this, 'onLicence'),
            $priority
        );
    }

    /**
     * @param RouteParam $e
     */
    public function onLicence(RouteParam $e)
    {
        $licenceId = $e->getValue();
        $licence = $this->getLicenceMarkerData($licenceId);

        $this->getMarkerService()->addData('licence', $licence);
        $this->getMarkerService()->addData('continuationDetail', $licence['continuationMarker']);
        $this->getMarkerService()->addData('organisation', $licence['organisation']);
        $this->getMarkerService()->addData('cases', $licence['cases']);

        $this->getLicenceService()->setId($licenceId); //set default licence id for use in forms

        $this->getViewHelperManager()->get('placeholder')
            ->getContainer('licence')
            ->set($licence);

        $this->getViewHelperManager()->get('placeholder')
            ->getContainer('note')
            ->set(isset($licence['latestNote']['comment']) ? $licence['latestNote']['comment'] : '');
        $this->getViewHelperManager()->get('placeholder')
            ->getContainer('isPriorityNote')
            ->set(isset($licence['latestNote']['priority']) && $licence['latestNote']['priority'] === 'Y');

        $this->showHideButtons($licence);
        $this->hideSurrenderMenu($licence);

        $licenceCategoryId = $licence['goodsOrPsv']['id'] ?? null;
        $navigationService = $this->getMainNavigationService();

        if ($licenceCategoryId === RefData::LICENCE_CATEGORY_GOODS_VEHICLE) {
            $navigationService->findOneById('licence_bus')->setVisible(0);
        }

        if ($licenceCategoryId === RefData::LICENCE_CATEGORY_PSV) {
            $communityLicencesNav = $navigationService->findOneById('licence_community_licences');
            $communityLicencesNav->setLabel(
                $communityLicencesNav->getLabel() . '.psv'
            );
        }

        if (isset($licence['vehicleType']['id']) && $licence['vehicleType']['id'] == RefData::APP_VEHICLE_TYPE_LGV) {
            $operatingCentresNav = $navigationService->findOneById('licence_operating_centres');
            $operatingCentresNav->setLabel(
                $operatingCentresNav->getLabel() . '.lgv'
            );
        }

        if (!$licence['canHaveInspectionRequest']) {
            $this->getMainNavigationService()
                ->findOneBy('id', 'licence_processing_inspection_request')
                ->setVisible(false);
        }
    }

    /**
     * Get Licence Marker data
     *
     * @param int $licenceId
     *
     * @return array
     * @throws \RuntimeException
     */
    protected function getLicenceMarkerData($licenceId)
    {
        $query = $this->getAnnotationBuilderService()->createQuery(
            \Dvsa\Olcs\Transfer\Query\Licence\Licence::create(['id' => $licenceId])
        );

        $response = $this->getQueryService()->send($query);
        if (!$response->isOk()) {
            throw new \RuntimeException('Error getting licence markers');
        }

        return $response->getResult();
    }

    public function getAnnotationBuilderService()
    {
        return $this->annotationBuilderService;
    }

    public function getQueryService()
    {
        return $this->queryService;
    }

    public function setAnnotationBuilderService($annotationBuilderService)
    {
        $this->annotationBuilderService = $annotationBuilderService;
        return $this;
    }

    public function setQueryService($queryService)
    {
        $this->queryService = $queryService;
        return $this;
    }

    /**
     * Handle display of right-hand navigation buttons. Note, all buttons are
     * visible by default, they may get hidden according to certain conditions
     *
     * @param array $licence licence data
     */
    protected function showHideButtons($licence)
    {
        /** @var Laminas\Navigation\Navigation */
        $sidebarNav = $this->getNavigationService();

        // 'Quick actions' buttons
        $this->showHideVariationButton($licence, $sidebarNav);
        $this->showHidePrintButton($licence, $sidebarNav);

        // 'Decisions' buttons
        $this->showHideCurtailRevokeSuspendButtons($licence, $sidebarNav);
        $this->showHideSurrenderButton($licence, $sidebarNav);
        $this->showHideTerminateButton($licence, $sidebarNav);
        $this->showHideResetToValidButton($licence, $sidebarNav);
        $this->showHideUndoSurrenderButton($licence, $sidebarNav);
        $this->showHideUndoTerminateButton($licence, $sidebarNav);
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Create variation' button is shown or not
     */
    protected function showHideVariationButton($licence, $sidebarNav)
    {
        $licenceType = $licence['licenceType']['id'] ?? null;

        // If the licence type is special restricted we can't create a variation
        if ($licenceType === RefData::LICENCE_TYPE_SPECIAL_RESTRICTED) {
            $sidebarNav->findById('licence-quick-actions-create-variation')->setVisible(0);
            return false;
        }

        if (
            in_array(
                $licence['status']['id'],
                [
                RefData::LICENCE_STATUS_REVOKED,
                RefData::LICENCE_STATUS_TERMINATED,
                RefData::LICENCE_STATUS_SURRENDERED,
                RefData::LICENCE_STATUS_CONTINUATION_NOT_SOUGHT,
                RefData::LICENCE_STATUS_SURRENDER_UNDER_CONSIDERATION,
                ]
            )
        ) {
            $sidebarNav->findById('licence-quick-actions-create-variation')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Print' button is shown or not
     */
    protected function showHidePrintButton($licence, $sidebarNav)
    {
        $printStatuses = [
            RefData::LICENCE_STATUS_VALID,
            RefData::LICENCE_STATUS_CURTAILED,
            RefData::LICENCE_STATUS_SUSPENDED
        ];

        if (!in_array($licence['status']['id'], $printStatuses)) {
            $sidebarNav->findById('licence-quick-actions-print-licence')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Curtail' 'Revoke' and 'Suspend' buttons are shown or not
     */
    protected function showHideCurtailRevokeSuspendButtons($licence, $sidebarNav)
    {
        $licenceStatuses = [
            RefData::LICENCE_STATUS_VALID,
        ];
        // Buttons never shown if the licence is not valid
        if (!in_array($licence['status']['id'], $licenceStatuses)) {
            $sidebarNav->findById('licence-decisions-curtail')->setVisible(0);
            $sidebarNav->findById('licence-decisions-revoke')->setVisible(0);
            $sidebarNav->findById('licence-decisions-suspend')->setVisible(0);
        }

        // Buttons are  hidden if there is a queued revocation, curtailment or suspension
        if ($this->hasPendingStatusChange($licence)) {
            $sidebarNav = $this->getNavigationService();
            $sidebarNav->findById('licence-decisions-curtail')->setVisible(0);
            $sidebarNav->findById('licence-decisions-revoke')->setVisible(0);
            $sidebarNav->findById('licence-decisions-suspend')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Reset to valid' button is shown or not
     */
    protected function showHideResetToValidButton($licence, $sidebarNav)
    {
        $statuses = [
            RefData::LICENCE_STATUS_REVOKED,
            RefData::LICENCE_STATUS_CURTAILED,
            RefData::LICENCE_STATUS_SUSPENDED,
            RefData::LICENCE_STATUS_CONTINUATION_NOT_SOUGHT,
        ];

        if (!in_array($licence['status']['id'], $statuses)) {
            $sidebarNav->findById('licence-decisions-reset-to-valid')->setVisible(0);
        }
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Surrender' button is shown or not
     */
    protected function showHideSurrenderButton($licence, $sidebarNav)
    {
        if ($this->isSelfServeSurrender($licence)) {
            $sidebarNav->findById('licence-decisions-surrender')->setVisible(0);
            return false;
        }

        if ($licence['status']['id'] === RefData::LICENCE_STATUS_SURRENDER_UNDER_CONSIDERATION) {
            $sidebarNav->findById('licence-decisions-surrender')->setVisible(0);
            return false;
        }
        // The 'surrender' button is never shown if the licence is not valid
        if ($licence['status']['id'] !== RefData::LICENCE_STATUS_VALID) {
            $sidebarNav->findById('licence-decisions-surrender')->setVisible(0);
            return false;
        }

        // The 'surrender' button is hidden if there is a queued revocation,
        // curtailment or suspension
        if ($this->hasPendingStatusChange($licence)) {
            $sidebarNav->findById('licence-decisions-surrender')->setVisible(0);
            return false;
        }
        return true;
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Terminate' button is shown or not
     */
    protected function showHideTerminateButton($licence, $sidebarNav)
    {
        // The 'terminate' button is never shown if the licence is not valid
        if ($licence['status']['id'] !== RefData::LICENCE_STATUS_VALID) {
            $sidebarNav->findById('licence-decisions-terminate')->setVisible(0);
            return false;
        }

        // The 'terminate' button is only applicable for PSV licences
        if ($licence['goodsOrPsv']['id'] != RefData::LICENCE_CATEGORY_PSV) {
            $sidebarNav->findById('licence-decisions-terminate')->setVisible(0);
            return false;
        }

        // The 'terminate' button is hidden if there is a queued revocation,
        // curtailment or suspension
        if ($this->hasPendingStatusChange($licence)) {
            $sidebarNav->findById('licence-decisions-terminate')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Undo termination' button is shown or not
     */
    protected function showHideUndoTerminateButton($licence, $sidebarNav)
    {
        // The 'Undo termination' button is only shown if the licence is terminated
        if ($licence['status']['id'] !== RefData::LICENCE_STATUS_TERMINATED) {
            $sidebarNav->findById('licence-decisions-undo-terminate')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array                      $licence    licence data
     * @param Laminas\Navigation\Navigation $sidebarNav side bar navigation object
     *
     * @return boolean whether 'Undo surrender' button is shown or not
     */
    protected function showHideUndoSurrenderButton($licence, $sidebarNav)
    {
        // The 'Undo surrender' button is only shown if the licence is surrendered
        if ($licence['status']['id'] !== RefData::LICENCE_STATUS_SURRENDERED) {
            $sidebarNav->findById('licence-decisions-undo-surrender')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * Helper method to check for pending status changes for a licence,
     */
    protected function hasPendingStatusChange($licence)
    {
        $licenceStatuses = [
            RefData::LICENCE_STATUS_REVOKED,
            RefData::LICENCE_STATUS_CURTAILED,
            RefData::LICENCE_STATUS_SUSPENDED,
        ];

        foreach ($licence['licenceStatusRules'] as $rule) {
            if (empty($rule['startProcessedDate']) && in_array($rule['licenceStatus']['id'], $licenceStatuses)) {
                return true;
            }
        }

        return false;
    }

    private function isSelfServeSurrender(array $licence): bool
    {
        $surrender = null;
        if ($licence['status']['id'] === RefData::LICENCE_STATUS_SURRENDER_UNDER_CONSIDERATION) {
            try {
                $surrender = $this->getSurrenderService()->fetchSurrenderData($licence['id']);
            } catch (DataServiceException $responseException) {
                //unable to get data fail gracefully
                return false;
            }
            return $surrender['signatureType']['id'] !== null;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getSurrenderService()
    {
        return $this->surrenderService;
    }

    /**
     * @param $licence
     */
    protected function hideSurrenderMenu($licence): void
    {
        if ($licence['status']['id'] !== RefData::LICENCE_STATUS_SURRENDER_UNDER_CONSIDERATION) {
            $this->getMainNavigationService()->findOneById('licence_surrender')->setVisible(0);
        }
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Licence
    {
        $this->setViewHelperManager($container->get('ViewHelperManager'));
        $this->setLicenceService($container->get('DataServiceManager')->get('Common\Service\Data\Licence'));
        $this->setNavigationService($container->get('right-sidebar'));
        $this->setMainNavigationService($container->get('Navigation'));
        $this->setMarkerService($container->get(\Olcs\Service\Marker\MarkerService::class));
        $this->setAnnotationBuilderService($container->get('TransferAnnotationBuilder'));
        $this->setQueryService($container->get('QueryService'));
        $this->setSurrenderService($container->get('DataServiceManager')->get(Surrender::class));
        return $this;
    }
}
