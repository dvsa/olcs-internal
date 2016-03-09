<?php

/**
 * Si Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Cases\Penalty;

use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\CaseControllerInterface;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Zend\View\Model\ViewModel;
use Dvsa\Olcs\Transfer\Command\Cases\Si\SendResponse as SendResponseCmd;
use Olcs\Mvc\Controller\ParameterProvider\GenericItem;
use Olcs\Data\Mapper\GenericFields;
use Dvsa\Olcs\Transfer\Query\Cases\Si\GetList as ListDto;
use Dvsa\Olcs\Transfer\Query\Cases\Cases as CaseDto;
use Zend\Http\Response as HttpResponse;

/**
 * Si Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class SiController extends AbstractInternalController implements CaseControllerInterface, LeftViewProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_details_penalties';

    protected $tableName = 'serious-infringement';
    protected $tableViewTemplate = 'sections/cases/pages/serious-infringement';

    protected $mapperClass = GenericFields::class;
    protected $listDto = ListDto::class;
    protected $listVars = ['case' => 'case'];

    protected $crudConfig = [
        'send' => ['requireRows' => false]
    ];

    /**
     * Any inline scripts needed in this section
     *
     * @var array
     */
    protected $inlineScripts = array(
        'indexAction' => ['table-actions']
    );

    /**
     * @return ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/cases/partials/left');

        return $view;
    }

    public function indexAction(){
        $case = $this->handleQuery(CaseDto::create(['id' => $this->params()->fromRoute('case')]));
        $this->placeholder()->setPlaceholder('case', $case->getResult());

        return parent::indexAction();
    }

    /**
     * Sends the response back to Erru

     * @return HttpResponse
     */
    public function sendAction()
    {
        return $this->processCommand(new GenericItem(['case' => 'case']), SendResponseCmd::class);
    }
}
