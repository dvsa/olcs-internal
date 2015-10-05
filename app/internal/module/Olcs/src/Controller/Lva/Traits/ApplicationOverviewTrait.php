<?php

namespace Olcs\Controller\Lva\Traits;

use Common\Form\Elements\InputFilters\SelectEmpty as SelectElement;
use Zend\View\Model\ViewModel;
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
     */
    public function indexAction()
    {
        if ($this->getRequest()->isPost() && $this->isButtonPressed('cancel')) {
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

        if ($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $dtoData = $this->mapData($form->getData());
                $cmd = OverviewCmd::create($dtoData);
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
        $viewData = $this->getServiceLocator()->get('Helper\ApplicationOverview')
            ->getViewData($application, $this->lva);

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

    protected function getOverviewForm()
    {
        return $this->getServiceLocator()->get('Helper\Form')
            ->createForm('ApplicationOverview');
    }

    protected function getOverviewData($applicationId)
    {
        $query = OverviewQry::create(['id' => $applicationId]);
        $response = $this->handleQuery($query);
        return $response->getResult();
    }

    /**
     * @param array $application application overview data
     * @return array
     */
    protected function formatDataForForm($application)
    {
        return [
            'details' => [
                'receivedDate'           => $application['receivedDate'],
                'targetCompletionDate'   => $application['targetCompletionDate'],
                'leadTcArea'             => $application['licence']['organisation']['leadTcArea']['id'],
                'translateToWelsh'       => $application['licence']['translateToWelsh'],
                'overrideOppositionDate' => $application['overrideOoo'],
                'version'                => $application['version'],
                'id'                     => $application['id'],
            ],
            'tracking' => $application['applicationTracking'],
        ];
    }

    /**
     * @param Zend\Form\Form $form
     * @param array $licence licence overview data
     * @param array $application application overview data
     */
    protected function alterForm($form, $licence, $application)
    {
        // build up the tracking fieldset dynamically, based on relevant sections
        $fieldset = $form->get('tracking');
        $stringHelper = $this->getServiceLocator()->get('Helper\String');
        $sections = $this->getAccessibleSections();
        $options = $application['valueOptions']['tracking'];
        foreach ($sections as $section) {
            $selectProperty = lcfirst($stringHelper->underscoreToCamel($section)) . 'Status';
            $select = new SelectElement($selectProperty);
            $select->setValueOptions($options);
            $select->setLabel('section.name.'.$section);
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

        return $form;
    }

    protected function mapData($formData)
    {
        $data = [
            'id' => $formData['details']['id'],
            'version' => $formData['details']['version'],
            'leadTcArea' => $formData['details']['leadTcArea'],
            'tracking' => $formData['tracking'],
            'overrideOppositionDate' => $formData['details']['overrideOppositionDate'],
        ];
        if (isset($formData['details']['receivedDate'])) {
            $data['receivedDate'] = $formData['details']['receivedDate'];
        }
        if (isset($formData['details']['targetCompletionDate'])) {
            $data['targetCompletionDate'] = $formData['details']['targetCompletionDate'];
        }

        return $data;
    }
}
