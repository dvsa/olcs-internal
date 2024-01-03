<?php

namespace Olcs\Controller\Messages;

use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Form\Model\Form\NewMessage;
use Common\FeatureToggle;
use Olcs\Form\Model\Form\Cases;
use Common\Data\Mapper\DefaultMapper;
use Dvsa\Olcs\Transfer\Query\Messaging\ApplicationLicenceList\ByLicenceToOrganisation;

class LicenceNewConversationController extends AbstractInternalController implements LeftViewProvider
{
    protected $navigationId = 'conversation_list_new_conversation';

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

        /**
         * var Common\Service\Cqrs\Response $data
         */
        $data = $this->handleQuery(
            ByLicenceToOrganisation::create(['licence' => $this->params()->fromRoute('licence')])
        );

        $applicationLicenceArray = json_decode($data->getHttpResponse()->getBody(), true);

        $options = array();

        if($applicationLicenceArray['results']['licences']){
            $options['licence'] = [
                'label' => 'Licences',
                'options' => $applicationLicenceArray['results']['licences'],
            ];
        }

        if($applicationLicenceArray['results']['applications']){
            $options['application'] = [
                'label' => 'Applications',
                'options' => $applicationLicenceArray['results']['applications'],
            ];
        }

        $appLicNoSelect->setValueOptions($options);

        return $form;
    }
}