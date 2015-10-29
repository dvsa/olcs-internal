<?php

/**
 * Case Opposition Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
namespace Olcs\Controller\Cases\Opposition;

use Dvsa\Olcs\Transfer\Command\Opposition\CreateOpposition as CreateDto;
use Dvsa\Olcs\Transfer\Command\Opposition\DeleteOpposition as DeleteDto;
use Dvsa\Olcs\Transfer\Command\Opposition\UpdateOpposition as UpdateDto;
use Dvsa\Olcs\Transfer\Query\Opposition\Opposition as ItemDto;
use Dvsa\Olcs\Transfer\Query\Opposition\OppositionList as OppositionListDto;
use Dvsa\Olcs\Transfer\Query\EnvironmentalComplaint\EnvironmentalComplaintList as EnvComplaintListDto;
use Dvsa\Olcs\Transfer\Query\Cases\CasesWithOppositionDates as CasesWithOppositionDatesDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\CaseControllerInterface;
use Olcs\Data\Mapper\Opposition as Mapper;
use Olcs\Form\Model\Form\Opposition as Form;
use Olcs\Mvc\Controller\ParameterProvider\GenericItem;
use Olcs\Mvc\Controller\ParameterProvider\GenericList;

/**
 * Case Opposition Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
class OppositionController extends AbstractInternalController implements CaseControllerInterface
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_opposition';

    protected $routeIdentifier = 'opposition';

    protected $crudConfig = [
        'generate' => ['requireRows' => true],
    ];

    /**
     * Variables for controlling details view rendering
     * details view and itemDto are required.
     */
    protected $itemDto = ItemDto::class;
    // 'id' => 'opposition', to => from
    protected $itemParams = ['id' => 'opposition'];

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = Form::class;
    protected $updateCommand = UpdateDto::class;
    protected $mapperClass = Mapper::class;
    protected $addContentTitle = 'Add opposition';
    protected $editContentTitle = 'Edit opposition';

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $createCommand = CreateDto::class;

    /**
     * Form data for the add form.
     *
     * Format is name => value
     * name => "route" means get value from route,
     * see conviction controller
     *
     * @var array
     */
    protected $defaultData = [
        'case' => 'route'
    ];

    /**
     * Variables for controlling the delete action.
     * Command is required, as are itemParams from above
     */
    protected $deleteCommand = DeleteDto::class;
    protected $deleteParams = ['id' => 'opposition'];
    protected $deleteModalTitle = 'Delete Opposition';

    /**
     * Any inline scripts needed in this section
     *
     * @var array
     */
    protected $inlineScripts = array(
        'indexAction' => ['table-actions'],
        'addAction' => ['forms/opposition'],
        'editAction' => ['forms/opposition'],
    );

    public function indexAction()
    {
        $this->setupOppositionsTable();

        $this->setupEnvironmentComplaintsTable();

        return $this->details(
            CasesWithOppositionDatesDto::class,
            new GenericItem(['id' => 'case']),
            'details',
            'sections/cases/pages/opposition',
            'Opposition details'
        );
    }

    private function setupOppositionsTable()
    {
        $this->index(
            OppositionListDto::class,
            new GenericList(['case'], 'id'),
            'oppositionsTable',
            'opposition',
            $this->tableViewTemplate
        );
    }

    private function setupEnvironmentComplaintsTable()
    {
        $this->index(
            EnvComplaintListDto::class,
            new GenericList(['case'], 'id'),
            'envComplaintsTable',
            'environmental-complaints',
            $this->tableViewTemplate
        );
    }

    public function detailsAction()
    {
        return $this->notFoundAction();
    }

    /**
     * Generate action.
     */
    public function generateAction()
    {
        return $this->redirect()->toRoute(
            'case_licence_docs_attachments/entity/generate',
            [
                'case' => $this->params()->fromRoute('case'),
                'entityType' => 'opposition',
                'entityId' => $this->params()->fromRoute('opposition')
            ]
        );
    }

    /**
     * Alter Form for add
     *
     * @param \Common\Controller\Form $form
     * @param array $initialData
     * @return \Common\Controller\Form
     */
    public function alterFormForAdd($form, $initialData)
    {
        return $this->alterFormForCase($form, $initialData);
    }

    /**
     * Alter Form for edit
     *
     * @param \Common\Controller\Form $form
     * @param array $initialData
     * @return \Common\Controller\Form
     */
    public function alterFormForEdit($form, $initialData)
    {
        return $this->alterFormForCase($form, $initialData);
    }

    /**
     * Alter Form based on Case details
     *
     * @param \Common\Controller\Form $form
     * @param array $initialData
     * @return \Common\Controller\Form
     */
    private function alterFormForCase($form, $initialData)
    {
        // get the case with opposition dates
        $caseWithOppositionDates = $this->getCaseWithOppositionDates();

        if (!empty($caseWithOppositionDates['oorDate'])) {
            // set oor date
            $oorObj = new \DateTime($caseWithOppositionDates['oorDate']);
            $oorString = !empty($oorObj) ? $oorObj->format(\DATE_FORMAT) : '';

            $form->get('fields')
                ->get('outOfRepresentationDate')
                ->setLabel('Out of representation ' . $oorString);
        }

        if (!empty($caseWithOppositionDates['oooDate'])) {
            // set ooo date
            $oooObj = new \DateTime($caseWithOppositionDates['oooDate']);
            $oooString = !empty($oooObj) ? $oooObj->format(\DATE_FORMAT) : '';

            $form->get('fields')
                ->get('outOfObjectionDate')
                ->setLabel('Out of objection ' . $oooString);
        }

        if (!empty($caseWithOppositionDates['licence']['goodsOrPsv']['id'])
            && ($caseWithOppositionDates['licence']['goodsOrPsv']['id'] == 'lcat_psv')
        ) {
            // modify opposition type options
            $options = $form->get('fields')
                ->get('oppositionType')
                ->getValueOptions();
            unset($options['otf_eob']);
            unset($options['otf_rep']);

            $form->get('fields')
                ->get('oppositionType')
                ->setValueOptions($options);
        }

        if (!empty($caseWithOppositionDates['application'])) {
            // remove licence operating centres
            $form->get('fields')
                ->remove('licenceOperatingCentres');
        } else {
            // remove application operating centres
            $form->get('fields')
                ->remove('applicationOperatingCentres');
        }

        return $form;
    }

    protected function getCaseWithOppositionDates()
    {
        // get the case with opposition dates
        $paramProvider = new GenericItem(['id' => 'case']);
        $paramProvider->setParams($this->plugin('params'));

        $params = $paramProvider->provideParameters();
        $query = CasesWithOppositionDatesDto::create($params);

        $response = $this->handleQuery($query);

        if ($response->isOk()) {
            $caseWithOppositionDates = $response->getResult();
        }

        return $caseWithOppositionDates;
    }
}
