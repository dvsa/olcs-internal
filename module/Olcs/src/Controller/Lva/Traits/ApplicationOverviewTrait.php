<?php

namespace Olcs\Controller\Lva\Traits;

use Common\Form\Elements\InputFilters\SelectEmpty as SelectElement;
use Common\RefData;
use Common\Service\Cqrs\Response;
use Laminas\View\Model\ViewModel;
use Dvsa\Olcs\Transfer\Query\Application\Overview as OverviewQry;
use Dvsa\Olcs\Transfer\Command\Application\Overview as OverviewCmd;

/**
 * This trait enables the Application and Variation overview controllers to
 * share identical behaviour
 */
trait ApplicationOverviewTrait
{
    /**
     * Application overview
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost() && $this->isButtonPressed('cancel')) {
            $this->addSuccessMessage('flash-discarded-changes');
            return $this->reload();
        }

        // get application and licence data (we need this regardless of GET/POST
        // in order to alter the form correctly)
        $applicationId = $this->getIdentifier();
        $application = $this->getOverviewData($applicationId);

        $licence = $application['licence'];

        $form = $this->getOverviewForm();
        $this->alterForm($form, $licence, $application);

        if ($request->isPost()) {
            $data = (array) $request->getPost();

            $form->setData($data);

            if ($form->isValid()) {
                $dtoData = $this->mapData($form->getData());
                $cmd = OverviewCmd::create($dtoData);

                /** @var Response $response */
                $response = $this->handleCommand($cmd);
                if ($response->isOk()) {
                    $this->addSuccessMessage('application.overview.saved');

                    if ($this->isButtonPressed('saveAndContinue')) {
                        return $this->redirect()->toRoute(
                            'lva-'.$this->lva.'/type_of_licence',
                            ['application' => $applicationId]
                        );
                    }
                    return $this->reload();
                } else {
                    $this->addErrorMessage('application.overview.save.failed');
                }
            }
        } else {
            $formData = $this->formatDataForForm($application);
            $form->setData($formData);
        }

        // Render the view
        /** @var \Olcs\Service\Helper\ApplicationOverviewHelperService $helper */
        $helper = $this->getServiceLocator()->get('Helper\ApplicationOverview');
        $viewData = $helper->getViewData($application, $this->lva);

        $content = new ViewModel(
            array_merge(
                $viewData,
                [
                    'form' => $form,
                    'title' => 'Overview'
                ]
            )
        );
        $content->setTemplate('sections/application/pages/overview');

        return $this->render($content);
    }

    /**
     * get method Overview Form
     *
     * @return \Laminas\Form\FormInterface
     */
    protected function getOverviewForm()
    {
        return $this->getServiceLocator()->get('Helper\Form')
            ->createForm('ApplicationOverview');
    }

    /**
     * get Method overview daya
     *
     * @param int $applicationId applicationId
     *
     * @return mixed
     */
    protected function getOverviewData($applicationId)
    {
        $query = OverviewQry::create(['id' => $applicationId]);

        $response = $this->handleQuery($query);
        return $response->getResult();
    }

    /**
     * formate Fata for form
     *
     * @param array $application application overview data
     *
     * @return array
     */
    protected function formatDataForForm($application)
    {
        return [
            'details' => [
                'receivedDate'           => $application['receivedDate'] ?? null,
                'targetCompletionDate'   => $application['targetCompletionDate'] ?? null,
                'leadTcArea'             => $application['licence']['organisation']['leadTcArea']['id'] ?? null,
                'translateToWelsh'       => $application['licence']['translateToWelsh'] ?? null,
                'overrideOppositionDate' => $application['overrideOoo'] ?? null,
                'version'                => $application['version'] ?? null,
                'id'                     => $application['id'] ?? null,
            ],
            'tracking' => $application['applicationTracking'] ?? null,
        ];
    }

    /**
     * alter form
     *
     * @param \Laminas\Form\FormInterface $form        form
     * @param array                    $licence     licence overview data
     * @param array                    $application application overview data
     *
     * @return Laminas/Form/FormInterface
     */
    protected function alterForm($form, $licence, $application)
    {
        // build up the tracking fieldset dynamically, based on relevant sections
        $fieldset = $form->get('tracking');
        $stringHelper = $this->getServiceLocator()->get('Helper\String');

        $options = $application['valueOptions']['tracking'];

        $licenceCategoryId = $application['licence']['goodsOrPsv']['id'];
        $vehicleTypeId = $application['vehicleType']['id'];

        $sections = $this->getAccessibleSections();
        foreach ($sections as $section) {
            $selectProperty = lcfirst($stringHelper->underscoreToCamel($section)) . 'Status';

            $select = new SelectElement($selectProperty);
            $select->setValueOptions($options);

            $label = 'section.name.' . $section;
            if ($section == 'community_licences' && $licenceCategoryId == RefData::LICENCE_CATEGORY_PSV) {
                $label .= '.psv';
            } elseif ($section == 'operating_centres' && $vehicleTypeId == RefData::APP_VEHICLE_TYPE_LGV) {
                $label .= '.lgv';
            }
            $select->setLabel($label);

            $fieldset->add($select);
        }

        // modify button label (it should be 'Save' not 'Save & return' as per AC)
        $form->get('form-actions')->get('save')->setLabel('Save');

        $form->get('details')->get('leadTcArea')->setValueOptions(
            $licence['valueOptions']['trafficAreas']
        );

        if ($licence['trafficArea']['isWales'] !== true) {
            $this->getServiceLocator()->get('Helper\Form')->remove($form, 'details->translateToWelsh');
        }

        $this->alterFormForLva($form);

        return $form;
    }

    /**
     * mapData
     *
     * @param array $formData formData
     *
     * @return array
     */
    protected function mapData($formData)
    {
        $details = $formData['details'];

        $data = [
            'id' => $details['id'],
            'version' => $details['version'],
            'leadTcArea' => $details['leadTcArea'],
            'tracking' => $formData['tracking'],
            'overrideOppositionDate' => $details['overrideOppositionDate'],
            'validateAppCompletion' => true,
        ];

        if (isset($details['receivedDate'])) {
            $data['receivedDate'] = $details['receivedDate'];
        }

        if (isset($details['targetCompletionDate'])) {
            $data['targetCompletionDate'] = $details['targetCompletionDate'];
        }

        return $data;
    }
}
