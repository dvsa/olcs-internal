<?php

namespace Olcs\Controller\Messages;

use Common\Data\Mapper\DefaultMapper;
use Common\FeatureToggle;
use Dvsa\Olcs\Transfer\Query\Messaging\ApplicationLicenceList\ByApplicationToOrganisation;
use Dvsa\Olcs\Transfer\Query\Messaging\ApplicationLicenceList\ByLicenceToOrganisation;
use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Form\Model\Form\Conversation;
use RuntimeException;

class AbstractCreateConversationController extends AbstractInternalController implements LeftViewProvider
{
    protected $navigationId = 'licence_new_conversation';

    protected $mapperClass = DefaultMapper::class;

    protected $formClass = Conversation::class;
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

    public function alterFormForAdd($form)
    {
        $appLicNoSelect = $form->get('fields')->get('appLicNo');

        if ($this->params()->fromRoute('licence')){
            $licenceId = $this->params()->fromRoute('licence');
            $data = $this->handleQuery(
                ByLicenceToOrganisation::create(['licence' => $licenceId])
            );
        } elseif ($this->params()->fromRoute('application')) {
            $applicationId = $this->params()->fromRoute('application');
            $data = $this->handleQuery(
                ByApplicationToOrganisation::create(['application' => $applicationId])
            );
        } else {
            throw new RuntimeException('Error: licence or application required');
        }

        $applicationLicenceArray = json_decode($data->getHttpResponse()->getBody(), true);

        $options = [];

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
