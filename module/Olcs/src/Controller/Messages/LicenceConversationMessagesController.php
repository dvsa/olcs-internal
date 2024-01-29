<?php

namespace Olcs\Controller\Messages;

use Common\Form\Form;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Script\ScriptFactory;
use Laminas\Http\Response;
use Laminas\Navigation\Navigation;
use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Dvsa\Olcs\Transfer\Query\Messaging\Messages\ByConversation;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Interfaces\LicenceControllerInterface;
use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\FeatureToggle;
use Olcs\Form\Model\Form\LicenceMessageActions;
use Olcs\Form\Model\Form\LicenceMessageReply;
use Olcs\Mvc\Controller\ParameterProvider\GenericList;
use Dvsa\Olcs\Transfer\Command\Messaging\Message\Create as CreateMessageCommand;

class LicenceConversationMessagesController
    extends AbstractInternalController
    implements LeftViewProvider, LicenceControllerInterface, ToggleAwareInterface
{
    protected $navigationId = 'conversations';
    protected $listVars = ['licence', 'conversation'];
    protected $listDto = ByConversation::class;
    protected $tableName = 'messages-list';
    protected $tableViewTemplate = 'pages/conversation/messages';
    protected $routeIdentifier = 'messages';
    protected $toggleConfig = [
        'default' => [
            FeatureToggle::MESSAGING
        ],
    ];
    protected ScriptFactory $scriptFactory;

    public function __construct(
        TranslationHelperService    $translationHelper,
        FormHelperService           $formHelper,
        FlashMessengerHelperService $flashMessenger,
        Navigation                  $navigation,
        ScriptFactory               $scriptFactory
    )
    {
        parent::__construct($translationHelper, $formHelper, $flashMessenger, $navigation);

        $this->scriptFactory = $scriptFactory;
    }

    /**
     * @inheritDoc
     */
    public function indexAction()
    {
        $this->scriptFactory->loadFiles(['table-actions']);

        $paramProvider = (new GenericList($this->listVars, $this->defaultTableSortField, $this->defaultTableOrderField))
            ->setDefaultLimit($this->defaultTableLimit);
        $paramProvider->setParams($this->plugin('params'));
        $providedParameters = $this->modifyListQueryParameters($paramProvider->provideParameters());
        $response = $this->handleQuery($this->listDto::create($providedParameters));

        $replyForm = $this->getForm(LicenceMessageReply::class);
        $replyForm->get('id')->setValue($this->params()->fromRoute('conversation'));
        $this->placeholder()->setPlaceholder('send-reply', $replyForm);

        $actionsForm = $this->getForm(LicenceMessageActions::class);
        $actionsForm->get('id')->setValue($this->params()->fromRoute('conversation'));

        if ($response->getResult()['extra']['conversation']['isClosed']) {
            $actionsForm->get('form-actions')->get('close')->setAttribute('disabled', 'disabled');
            $actionsForm->get('form-actions')->get('close')->setAttribute(
                'class',
                'govuk-button govuk-button--warning govuk-button--disabled'
            );
        }

        $this->placeholder()->setPlaceholder('message-actions', $actionsForm);

        if (!$this->getRequest()->isPost()) {
            return parent::indexAction();
        }

        $action = strtolower($this->params()->fromPost('action'));
        switch ($action) {
            case 'end and archive conversation':
                $params = [
                    'licence' => $this->params()->fromRoute('licence'),
                    'conversation' => $this->params()->fromRoute('conversation'),
                    'action' => $this->params()->fromRoute('confirm'),
                ];
                return $this->redirect()->toRoute('licence/conversation/close', $params);
            case 'reply':
                return $this->parseReply($replyForm);
        }
    }

    /** @return Response|ViewModel */
    protected function parseReply(Form $form)
    {
        $form->setData((array)$this->params()->fromPost());
        $form->get('id')->setValue($this->params()->fromRoute('conversation'));

        if (!$form->isValid()) {
            return parent::indexAction();
        }

        $response = $this->handleCommand(CreateMessageCommand::create([
            'conversation' => $this->params()->fromRoute('conversation'),
            'messageContent' => $form->get('form-actions')->get('reply')->getValue()
        ]));

        if ($response->isOk()) {
            return $this->redirect()->toRoute('licence/conversation/view', $this->params()->fromRoute());
        }

        $this->handleErrors($response->getResult());

        return parent::indexAction();
    }

    public function getLeftView(): ViewModel
    {
        $view = new ViewModel();
        $view->setTemplate('sections/messages/partials/left');

        return $view;
    }

    public function getRightView(): ViewModel
    {
        $view = new ViewModel();
        $view->setTemplate('sections/licence/partials/right');

        return $view;
    }
}
