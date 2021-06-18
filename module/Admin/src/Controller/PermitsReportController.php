<?php

declare(strict_types = 1);

namespace Admin\Controller;

use Admin\Form\Model\Form\PermitsReport;
use Common\Form\Form;
use Dvsa\Olcs\Transfer\Command\Permits\QueueReport;
use Dvsa\Olcs\Transfer\Query\Permits\ReportList;
use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;

class PermitsReportController extends AbstractInternalController implements LeftViewProvider
{
    protected $navigationId = 'admin-dashboard/admin-report/permits';

    /**
     * Left View setting
     *
     * @return ViewModel
     */
    public function getLeftView(): ViewModel
    {
        $view = new ViewModel(
            [
                'navigationId' => 'admin-dashboard/admin-report',
                'navigationTitle' => 'Reports'
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }

    /**
     * Process action - Index
     *
     * @return  ViewModel
     */
    public function indexAction(): ViewModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost('reportOptions');

            $startDate = $postData['startDate']['year']
                . '-' . $postData['startDate']['month']
                . '-' . $postData['startDate']['day'];
            $endDate = $postData['endDate']['year']
                . '-' . $postData['endDate']['month']
                . '-' . $postData['endDate']['day'];

            $command = QueueReport::create(
                [
                    'id' => $postData['id'],
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]
            );

            $flashMessenger = $this->getServiceLocator()->get('Helper\FlashMessenger');
            $response = $this->handleCommand($command);

            if ($response->isOk()) {
                $flashMessenger->addSuccessMessage('Report has been queued for generation');
            } elseif ($response->isClientError() || $response->isServerError()) {
                $this->handleErrors($response->getResult());
            }
        }

        $editViewTemplate = 'pages/crud-form';

        $form = $this->getForm(PermitsReport::class);
        $this->setSelectReportList($form);

        $this->placeholder()->setPlaceholder('form', $form);

        return $this->viewBuilder()->buildViewFromTemplate($editViewTemplate);
    }

    /**
     * Set the list of reports in the select element
     *
     * @param Form $form Form
     *
     * @return void
     * @throws \Exception
     */
    private function setSelectReportList(Form $form): void
    {
        $response = $this->handleQuery(ReportList::create([]));
        if (!$response->isOk()) {
            throw new \Exception(
                "Permit Reports: Unable to fetch report list - ". $response->getStatusCode()
            );
        }

        $options = [];
        foreach ($response->getResult()['results'] as $reportCode => $reportTitle) {
            $options[$reportCode] = $reportTitle;
        }

        $select = $form->get('reportOptions')->get('id');
        assert($select instanceof \Laminas\Form\Element\Select);
        $select->setValueOptions($options);
    }
}
