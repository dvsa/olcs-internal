<?php

namespace Olcs\Controller\Lva\Application;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\StringHelperService;
use Common\Service\Helper\TranslationHelperService;
use Dvsa\Olcs\Transfer\Command\Application\NotTakenUpApplication;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\AbstractApplicationDecisionController;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;
use ZfcRbac\Service\AuthorizationService;

/**
 * Application Not Taken Up Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class NotTakenUpController extends AbstractApplicationDecisionController
{
    use ApplicationControllerTrait;

    protected string $lva               = 'application';
    protected string $location          = 'internal';
    protected $cancelMessageKey  = 'application-not-ntu';
    protected $successMessageKey = 'application-ntu-successfully';
    protected $titleKey          = 'internal-application-ntu-title';

    protected FormHelperService $formHelper;

    protected StringHelperService $stringHelper;

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param TranslationHelperService $translationHelper ,
     * @param FormHelperService $formHelper
     * @param StringHelperService $stringHelper
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FlashMessengerHelperService $flashMessengerHelper,
        TranslationHelperService $translationHelper,
        FormHelperService $formHelper,
        StringHelperService $stringHelper
    ) {
        $this->formHelper = $formHelper;
        $this->stringHelper = $stringHelper;

        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $flashMessengerHelper,
            $translationHelper
        );
    }

    /**
     * get from
     *
     * @return \Laminas\Form\FormInterface
     */
    protected function getForm()
    {
        $request  = $this->getRequest();

        /** @var \Laminas\Form\FormInterface $form */
        $form = $this->formHelper->createFormWithRequest('GenericConfirmation', $request);

        // override default label on confirm action button
        $form->get('messages')->get('message')->setValue('internal-application-ntu-confirm');

        return $form;
    }

    /**
     * process Decision
     *
     * @param int   $id   id
     * @param array $data data
     *
     * @return \Common\Service\Cqrs\Response
     */
    protected function processDecision($id, $data)
    {
        return $this->handleCommand(
            NotTakenUpApplication::create(
                [
                    'id' => $id,
                ]
            )
        );
    }
}
