<?php
/**
 * User Management Controller
 */

namespace Admin\Controller;

use Common\Service\Data\Search\Search;
use Dvsa\Olcs\Transfer\Command\User\CreateUser as CreateDto;
use Dvsa\Olcs\Transfer\Command\User\UpdateUser as UpdateDto;
use Dvsa\Olcs\Transfer\Command\User\DeleteUser as DeleteDto;
use Dvsa\Olcs\Transfer\Query\User\User as ItemDto;
use Dvsa\Olcs\Transfer\Query\TransportManagerApplication\GetList as TransportManagerApplicationListDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Data\Mapper\User as Mapper;
use Admin\Form\Model\Form\User as Form;
use Zend\View\Model\ViewModel;

/**
 * User Management Controller
 */
class UserManagementController extends AbstractInternalController implements LeftViewProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'admin-dashboard/admin-user-management';

    /**
     * Any inline scripts needed in this section
     *
     * @var array
     */
    protected $inlineScripts = array(
        'indexAction' => ['table-actions'],
        'addAction' => ['forms/user-type'],
        'editAction' => ['forms/user-type'],
    );

    protected $routeIdentifier = 'user';

    /**
     * Variables for controlling details view rendering
     * details view and itemDto are required.
     */
    protected $itemDto = ItemDto::class;
    // 'id' => 'conviction', to => from
    protected $itemParams = ['id' => 'user'];

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = Form::class;
    protected $updateCommand = UpdateDto::class;
    protected $mapperClass = Mapper::class;
    protected $addContentTitle = 'Add user';
    protected $editContentTitle = 'Edit user';

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $createCommand = CreateDto::class;

    /**
     * Variables for controlling the delete action.
     * Command is required, as are itemParams from above
     */
    protected $deleteCommand = DeleteDto::class;
    protected $deleteParams = ['id' => 'user'];
    protected $deleteModalTitle = 'Delete user';

    public function getLeftView()
    {
        $view = new ViewModel(
            [
                'navigationId' => 'admin-dashboard/admin-user-management',
                'navigationTitle' => 'User management'
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }

    public function indexAction()
    {
        $data['search'] = '*';

        // update data with information from route, and rebind to form so that form data is correct
        $data['index'] = 'user';

        /** @var Search $searchService **/
        $searchService = $this->getServiceLocator()->get('DataServiceManager')->get(Search::class);

        $searchService->setQuery($this->getRequest()->getQuery())
            ->setRequest($this->getRequest())
            ->setIndex($data['index'])
            ->setSearch($data['search']);

        $this->placeholder()->setPlaceholder(
            $this->tableViewPlaceholderName,
            $searchService->fetchResultsTable()
        );

        $this->placeholder()->setPlaceholder('pageTitle', 'User management');

        return $this->viewBuilder()->buildViewFromTemplate('pages/table');
    }

    /**
     * Gets a from from either a built or custom form config.
     * @param type $type
     * @return type
     */
    public function getForm($type)
    {
        $form = parent::getForm($type);

        $request = $this->getRequest();
        $post = array();

        if ($request->isPost()) {
            $post = (array)$request->getPost();

            if ($post['userType']['userType'] === 'transport-manager') {
                $form = $this->processApplicationTransportManagerLookup($form);
            }
        }

        return $form;
    }

    /**
     * Presentation logic to process an application look up
     *
     * @param $form
     * @return \Zend\Form\Form
     */
    protected function processApplicationTransportManagerLookup($form)
    {
        $request = $this->getRequest();

        $post = ($request->isPost()) ? (array)$request->getPost() : [];

        // If we have clicked find application, persist the form
        if (isset($post['userType']['applicationTransportManagers']['search'])
            && !empty($post['userType']['applicationTransportManagers']['search'])) {
            $this->persist = false;
        }

        if (isset($post['userType']['applicationTransportManagers']['application'])) {
            $applicationId = trim($post['userType']['applicationTransportManagers']['application']);
        }

        if (empty($applicationId) || !is_numeric($applicationId)) {
            $form->get('userType')
                ->get('applicationTransportManagers')
                ->get('application')
                ->setMessages(array('Please enter a valid application number'));
        } else {
            $tmList = $this->fetchTmListOptionsByApplicationId($applicationId);

            if (empty($tmList)) {
                $form->get('userType')
                    ->get('applicationTransportManagers')
                    ->get('application')
                    ->setMessages(array('No transport managers found for application'));
            } else {
                $form->get('userType')
                    ->get('transportManager')
                    ->setValueOptions($tmList);
            }
        }

        return $form;
    }

    /**
     * Fetches a list of Transport Managers by application Id
     * @param integer $applicationId
     * @return array
     */
    protected function fetchTmListOptionsByApplicationId($applicationId)
    {
        $response = $this->handleQuery(
            TransportManagerApplicationListDto::create(
                [
                    'application' => $applicationId
                ]
            )
        );

        if ($response->isServerError() || $response->isClientError()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        $optionData = [];

        if ($response->isOk()) {
            $data = $response->getResult();

            foreach ($data['results'] as $datum) {
                $optionData[$datum['transportManager']['id']]
                    = $datum['transportManager']['homeCd']['person']['forename'] . ' ' .
                        $datum['transportManager']['homeCd']['person']['familyName'];
            }
        }

        return $optionData;
    }
}
