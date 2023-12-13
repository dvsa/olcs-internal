<?php

namespace Olcs\Controller\Messages;

use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Form\Model\Form\NewMessage;
use Common\FeatureToggle;
use Olcs\Form\Model\Form\Cases;
use Common\Data\Mapper\DefaultMapper;

class LicenceNewConversationController extends AbstractInternalController implements LeftViewProvider
{

    protected $navigationId = 'conversation_list_new_conversation';
    protected $listVars = ['licence','application'];
    // protected $itemDto = AnnualTestHistoryQuery::class;
    // protected $updateCommand = UpdateAnnualTestHistoryCommand::class;
    protected $mapperClass = DefaultMapper::class;
    protected $formClass = NewMessage::class;
    protected $toggleConfig = [
        'default' => [
            FeatureToggle::MESSAGING
        ],
    ];
    protected $inlineScripts = [
        'addAction' => ['forms/message-categories']
    ];

    /**
     * Get left view
     *
     * @return ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/messages/partials/left');

        return $view;
    }

    public function alterFormForAdd($form){

        $appLicNoSelect = $form->get('fields')->get('appLicNo');
        $data = ["1"=>"LI8ewyhwe"];
        $appLicNoSelect->setValueOptions($data);
        
        $response = $this->handleQuery(
            TransferQry\Organisation\Dashboard::create($params)
        );
        
        return $form;
    }
}