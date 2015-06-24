<?php
/**
 * History Controller
 */
namespace Olcs\Controller;

use Olcs\Controller\Interfaces\PageInnerLayoutProvider;
use Olcs\Controller\Interfaces\PageLayoutProvider;
use Olcs\Listener\CrudListener;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent as MvcEvent;
use Olcs\Logging\Log\ZendLogPsr3Adapter as Logger;

// for type hints
use Olcs\View\Builder\BuilderInterface as ViewBuilderInterface;
use Olcs\Mvc\Controller\Plugin;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Common\Service\Cqrs\Response;

/**
 * Abstract class to extend for BASIC list/edit/delete functions
 *
 * @TODO method to alter form depending on data retrieved
 * @TODO define post add/edit/delete redirect location as a parameter?
 * @TODO review navigation stuff...
 *
 * @method ViewBuilderInterface viewBuilder()
 * @method Plugin\Script script()
 * @method Plugin\Placeholder placeholder()
 * @method Plugin\Table table()
 * @method Response handleQuery(QueryInterface $query)
 * @method Response handleCommand(QueryInterface $query)
 * @method Plugin\Confirm confirm($string)
 */
abstract class AbstractInternalController extends AbstractActionController implements
    PageLayoutProvider,
    PageInnerLayoutProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = '';

    /**
     * Array of scripts, any scripts included in the array will be added for all actions
     * scripts can be included on a per action basis by defining the action name as a key mapping to an array of scripts
     * eg: ['global', 'deleteAction' => ['delete-script']]
     *
     * @var array
     */
    protected $inlineScripts = [];

    /*
     * Variables for controlling table/list rendering
     * tableName and listDto are required,
     * listVars probably needs to be defined every time but will work without
     *
     * both listvars and itemParams are an array of route params that are used for various operations
     * you can either specify an item (if the param name in the dto is the same as the route param or
     * you can specify a key => value pair to map route param (value) to dto param (key)
     */
    protected $tableViewPlaceholderName = 'table';
    protected $tableViewTemplate = 'partials/table';
    protected $defaultTableSortField = 'id';
    protected $tableName = '';
    protected $listDto = '';
    protected $listVars = [];

    /**
     * Variables for controlling details view rendering
     * details view template and itemDto are required.
     */
    protected $detailsViewTemplate = '';
    protected $detailsViewPlaceholderName = 'details';
    protected $itemDto = '';
    protected $itemParams = ['id'];

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = '';
    protected $updateCommand = '';
    protected $mapperClass = '';

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $createCommand = '';

    /**
     * Form data for the add form.
     *
     * Format is name => value
     * name => "route" means get value from route,
     * see conviction controller
     *
     * @var array
     */
    protected $defaultData = [];

    protected $routeIdentifier = 'id';

    /**
     * Variables for controlling the delete action.
     * Command is required, as are itemParams from above
     */
    protected $deleteCommand = '';
    protected $deleteModalTitle = 'internal.delete-action-trait.title';

    public function indexAction()
    {
        return $this->index(
            $this->listDto,
            $this->listVars,
            $this->defaultTableSortField,
            $this->tableViewPlaceholderName,
            $this->tableName,
            $this->tableViewTemplate
        );
    }

    public function detailsAction()
    {
        return $this->details(
            $this->itemDto,
            $this->itemParams,
            $this->detailsViewPlaceholderName,
            $this->detailsViewTemplate
        );
    }

    public function addAction()
    {
        return $this->add(
            $this->formClass,
            $this->defaultData,
            $this->createCommand,
            $this->mapperClass
        );
    }

    public function editAction()
    {
        return $this->edit(
            $this->formClass,
            $this->itemDto,
            $this->itemParams,
            $this->updateCommand,
            $this->mapperClass
        );
    }


    public function deleteAction()
    {
        return $this->delete(
            $this->itemParams,
            $this->itemDto,
            $this->deleteCommand,
            $this->deleteModalTitle
        );
    }

    final protected function index(
        $listDto,
        $paramNames,
        $defaultSort,
        $tableViewPlaceholderName,
        $tableName,
        $tableViewTemplate
    ) {
        $this->getLogger()->debug(__FILE__);
        $this->getLogger()->debug(__METHOD__);

        $listParams = $this->getListParams($paramNames, $defaultSort);
        $response = $this->handleQuery($listDto::create($listParams));

        if ($response->isNotFound()) {
            return $this->notFoundAction();
        }

        if ($response->isClientError() || $response->isServerError()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        if ($response->isOk()) {
            $data = $response->getResult();

            $this->placeholder()->setPlaceholder(
                $tableViewPlaceholderName,
                $this->table()->buildTable($tableName, $data, $listParams)
            );
        }

        return $this->viewBuilder()->buildViewFromTemplate($tableViewTemplate);
    }

    final protected function details($itemDto, $paramNames, $detailsViewPlaceHolderName, $detailsViewTemplate)
    {
        $this->getLogger()->debug(__FILE__);
        $this->getLogger()->debug(__METHOD__);

        $params = $this->getItemParams($paramNames);

        $this->getLogger()->debug('Params: ' . print_r($params, 1));

        $query = $itemDto::create($params);

        $this->getLogger()->debug('Item Query DTO: ' . print_r($query, 1));

        $response = $this->handleQuery($query);

        $this->getLogger()->debug('Response: ' . print_r($response, 1));

        if ($response->isNotFound()) {

            $this->getLogger()->debug("Not Found");

            return $this->notFoundAction();
        }

        if ($response->isClientError() || $response->isServerError()) {

            $this->getLogger()->debug("Client / Server Error");

            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        if ($response->isOk()) {

            $this->getLogger()->debug("OK");

            $data = $response->getResult();

            if (isset($data)) {
                $this->placeholder()->setPlaceholder($detailsViewPlaceHolderName, $data);
            }
        }

        return $this->viewBuilder()->buildViewFromTemplate($detailsViewTemplate);
    }

    final protected function add($formClass, $initialData, $createCommand, $mapperClass)
    {
        $this->getLogger()->debug(__FILE__);
        $this->getLogger()->debug(__METHOD__);

        $request = $this->getRequest();
        $action = ucfirst($this->params()->fromRoute('action'));
        $form = $this->getForm($formClass);
        $this->placeholder()->setPlaceholder('form', $form);

        $initialData = $mapperClass::mapFromResult($this->getDefaultFormData($initialData));

        $this->getLogger()->debug('Initial / Default Data: ' . print_r($initialData, 1));

        if (method_exists($this, 'alterFormFor' . $action)) {
            $form = $this->{alterFormFor . $action}($form, $initialData);
            $this->getLogger()->debug('Altered Form Data: ' . print_r($initialData, 1));
        }

        $form->setData($mapperClass::mapFromResult($initialData));

        if ($request->isPost()) {

            $this->getLogger()->debug('Is Post');

            $form->setData((array)$this->params()->fromPost());

            $this->getLogger()->debug('Raw Post Data: ' . print_r((array)$this->params()->fromPost(), 1));

            if ($form->isValid()) {

                $data = ArrayUtils::merge($initialData, $form->getData());

                $this->getLogger()->debug('Filtered Form Data: ' . print_r($data, 1));

                $commandData = $mapperClass::mapFromForm($data);

                $this->getLogger()->debug('Command Data: ' . print_r($createCommand::create($commandData), 1));

                $response = $this->handleCommand($createCommand::create($commandData));

                if ($response->isServerError()) {

                    $this->getLogger()->debug("Server Error");
                    $this->getLogger()->debug('Result: ' . print_r($response->getResult(), 1));

                    $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
                }

                if ($response->isClientError()) {

                    $this->getLogger()->debug("Client Error");
                    $this->getLogger()->debug('Result: ' . print_r($response->getResult(), 1));

                    $flashErrors = $mapperClass::mapFromErrors($form, $response->getResult());

                    foreach ($flashErrors as $error) {
                        $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage($error);
                    }
                }

                if ($response->isOk()) {
                    $this->getLogger()->debug("OK");

                    $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('Created record');
                    return $this->redirectToIndex();
                }
            }
        }

        return $this->viewBuilder()->buildViewFromTemplate('pages/crud-form');
    }

    /**
     * @param $formClass
     * @param $itemDto
     * @param $paramNames
     * @param $updateCommand
     * @param \Olcs\Data\Mapper\GenericFields $mapperClass
     * @return array|ViewModel
     */
    final protected function edit($formClass, $itemDto, $paramNames, $updateCommand, $mapperClass)
    {
        $this->getLogger()->debug(__FILE__);
        $this->getLogger()->debug(__METHOD__);

        $request = $this->getRequest();
        $action = ucfirst($this->params()->fromRoute('action'));
        $form = $this->getForm($formClass);
        $this->placeholder()->setPlaceholder('form', $form);

        if ($request->isPost()) {

            $this->getLogger()->debug("Is Post");

            $data = $this->params()->fromPost();

            $this->getLogger()->debug('Original Post Data: ' . print_r($data, 1));

            $form->setData($data);

            if ($form->isValid()) {

                $this->getLogger()->debug('Filtered Form Data: ' . print_r($form->getData(), 1));

                $commandData = $mapperClass::mapFromForm($form->getData());

                $this->getLogger()->debug('Command: ' . print_r($updateCommand::create($commandData), 1));

                $response = $this->handleCommand($updateCommand::create($commandData));

                if ($response->isServerError()) {

                    $this->getLogger()->debug("Server Error");
                    $this->getLogger()->debug('Result: ' . print_r($response->getResult(), 1));

                    $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
                }

                if ($response->isClientError()) {

                    $this->getLogger()->debug("Client Error");
                    $this->getLogger()->debug('Result: ' . print_r($response->getResult(), 1));

                    $flashErrors = $mapperClass::mapFromErrors($form, $response->getResult());
                    foreach ($flashErrors as $error) {
                        $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage($error);
                    }
                }

                if ($response->isOk()) {

                    $this->getLogger()->debug("OK");

                    $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('Updated record');
                    return $this->redirectToIndex();
                }
            }
        } else {

            $itemParams = $this->getItemParams($paramNames);

            $response = $this->handleQuery($itemDto::create($itemParams));

            if ($response->isNotFound()) {

                return $this->notFoundAction();
            }

            if ($response->isClientError() || $response->isServerError()) {

                $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
            }

            if ($response->isOk()) {

                $result = $response->getResult();

                $formData = $mapperClass::mapFromResult($result);

                if (method_exists($this, 'alterFormFor' . $action)) {
                    $form = $this->{alterFormFor . $action}($form, $formData);
                }

                $form->setData($formData);
            }
        }

        return $this->viewBuilder()->buildViewFromTemplate('pages/crud-form');
    }

    final protected function delete($paramNames, $itemDto, $deleteCommand, $modalTitle)
    {
        $this->getLogger()->debug(__FILE__);
        $this->getLogger()->debug(__METHOD__);

        $data = [];

        $response = $this->handleQuery($itemDto::create($this->getItemParams($paramNames)));

        if ($response->isNotFound()) {
            return $this->notFoundAction();
        }

        if ($response->isClientError() || $response->isServerError()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
            return $this->redirectToIndex();
        }

        $data = $response->getResult();

        // Ok, now we're happy that we're deleting a record that actually exists..

        $confirm = $this->confirm(
            'Are you sure you want to permanently delete the selected record(s)?'
        );

        if ($confirm instanceof ViewModel) {
            $this->placeholder()->setPlaceholder('title', $modalTitle);
            return $this->viewBuilder()->buildView($confirm);
        }

        /** @var \Dvsa\Olcs\Transfer\Command\AbstractDeleteCommand $deleteCommand */
        $response = $this->handleCommand($deleteCommand::create($data));

        if ($response->isNotFound()) {
            return $this->notFoundAction();
        }

        if ($response->isServerError() || $response->isClientError()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        if ($response->isOk()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('Deleted record');
        }

        return $this->redirectToIndex();
    }

    private function getListParams($paramNames, $defaultSort)
    {
        $params = [
            'page'    => $this->params()->fromQuery('page', 1),
            'sort'    => $this->params()->fromQuery('sort', $defaultSort),
            'order'   => $this->params()->fromQuery('order', 'DESC'),
            'limit'   => $this->params()->fromQuery('limit', 10),
        ];

        foreach ((array) $paramNames as $key => $varName) {
            if (is_int($key)) {
                $params[$varName] = $this->params()->fromRoute($varName);
            } else {
                $params[$key] = $this->params()->fromRoute($varName);
            }
        }

        return $params;
    }

    private function getDefaultFormData($arr)
    {
        $params = [];

        foreach ((array) $arr as $key => $value) {
            if ($value === 'route') {
                $params[$key] = $this->params()->fromRoute($key);
            } else {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    private function getItemParams($paramNames)
    {
        $params = [];

        foreach ((array) $paramNames as $key => $varName) {
            if (is_int($key)) {
                $params[$varName] = $this->params()->fromRoute($varName);
            } else {
                $params[$key] = $this->params()->fromRoute($varName);
            }
        }

        return $params;
    }

    public function redirectToIndex()
    {
        return $this->redirect()->toRouteAjax(
            null,
            ['action' => 'index', $this->routeIdentifier => null], // ID Not required for index.
            ['code' => '303'], // Why? No cache is set with a 303 :)
            true
        );
    }

    /**
     * @codeCoverageIgnore this is part of the event system.
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();

        $listener = new CrudListener($this, $this->routeIdentifier);
        $this->getEventManager()->attach($listener);

        if (method_exists($this, 'setNavigationCurrentLocation')) {
            $this->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setNavigationCurrentLocation'), 6);
        }

        $this->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, array($this, 'attachScripts'), -100);
    }

    final public function attachScripts(MvcEvent $event)
    {
        $action = static::getMethodFromAction($event->getRouteMatch()->getParam('action', 'not-found'));
        $scripts = $this->getScripts($action);

        $this->script()->addScripts($scripts);
    }


    private function getScripts($action)
    {
        $scripts = [];
        if (isset($this->inlineScripts[$action])) {
            $scripts = array_merge($scripts, $this->inlineScripts[$action]);
        }

        $callback = function ($item) {
            return !is_array($item);
        };
        $globalScripts = array_filter($this->inlineScripts, $callback);

        return array_merge($scripts, $globalScripts);
    }

    /**
     * Sets the navigation to that specified in the controller. Useful for when a controller is
     * 100% represented by a single navigation object.
     *
     * @see $this->navigationId
     *
     * @return boolean true
     */
    final public function setNavigationCurrentLocation()
    {
        $navigation = $this->getServiceLocator()->get('Navigation');
        if (!empty($this->navigationId)) {
            $navigation->findOneBy('id', $this->navigationId)->setActive();
        }

        return true;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getForm($name)
    {
        $form = $this->getServiceLocator()->get('Helper\Form')->createForm($name);
        $this->getServiceLocator()->get('Helper\Form')->setFormActionFromRequest($form, $this->getRequest());
        return $form;
    }

    /**
     * Utility method that returns an instance of the logger.
     *
     * @return Logger
     */
    public function getLogger()
    {
        return $this->getServiceLocator()->get('Logger');
    }
}
