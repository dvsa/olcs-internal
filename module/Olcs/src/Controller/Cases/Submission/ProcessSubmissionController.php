<?php

namespace Olcs\Controller\Cases\Submission;

use Common\Form\Elements\Custom\DateSelect;
use Dvsa\Olcs\Transfer\Command\Submission\AssignSubmission as AssignUpdateDto;
use Dvsa\Olcs\Transfer\Command\Submission\InformationCompleteSubmission as InformationCompleteDto;
use Dvsa\Olcs\Transfer\Query\Submission\Submission as ItemDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\CaseControllerInterface;
use Olcs\Data\Mapper\Submission as Mapper;
use Olcs\Form\Model\Form\SubmissionInformationComplete as CompleteForm;
use Olcs\Form\Model\Form\SubmissionSendTo as SendToForm;

/**
 * Process Submission Controller
 */
class ProcessSubmissionController extends AbstractInternalController implements CaseControllerInterface
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_submissions';

    /**
     * @var array
     */
    protected $redirectConfig = [
        'assign' => [
            'route' => 'submission',
            'action' => 'details',
            'options' => [
                'fragment' => 'submissionActions',
            ],
            'reUseParams' => true,
        ],
        'information-complete' => [
            'route' => 'submission',
            'action' => 'details',
            'options' => [
                'fragment' => 'submissionActions',
            ],
            'reUseParams' => true,
        ]
    ];

    /**
     * Variables for controlling details view rendering
     * details view and itemDto are required.
     */
    protected $itemDto = ItemDto::class;

    protected $itemParams = ['id' => 'submission'];

    protected $mapperClass = Mapper::class;

    /**
     * Generate form action to update submission, setting assigned_date, sender/recipient_user_ids
     *
     * @return array|\Laminas\View\Model\ViewModel
     */
    public function assignAction()
    {
        $this->formClass = SendToForm::class;
        $this->updateCommand = AssignUpdateDto::class;
        $this->editContentTitle = 'Assign submission';

        return $this->editAction();
    }

    protected function alterFormForAssign($form, $initialData)
    {
        if (isset($initialData['readOnlyFields']) && !empty($initialData['readOnlyFields'])) {
            foreach ($initialData['readOnlyFields'] as $field) {
                $readOnlyField = $form->get('fields')->get($field);
                if ($readOnlyField instanceof DateSelect) {
                    foreach ($readOnlyField->getElements() as $element) {
                        $element->setAttribute('readonly', true);
                    }
                } else {
                    $readOnlyField->setAttribute('readonly', true);
                }
            }
        }
        return $form;
    }

    /**
     * Generate form action to update submission, setting information_complete_date
     *
     * @return array|\Laminas\View\Model\ViewModel
     */
    public function informationCompleteAction()
    {
        $this->formClass = CompleteForm::class;

        $this->updateCommand = InformationCompleteDto::class;
        $this->editContentTitle = 'Set info complete';

        return $this->editAction();
    }
}
