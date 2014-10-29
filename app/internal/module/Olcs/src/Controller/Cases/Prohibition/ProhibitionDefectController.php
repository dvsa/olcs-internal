<?php

/**
 * Case Prohibition Defect Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Cases\Prohibition;

// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Prohibition Defect Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class ProhibitionDefectController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'id';

    /**
     * Table name string
     *
     * @var string
     */
    protected $tableName = 'prohibitionDefect';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'prohibition-defect';

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case';

    /**
     * For most case crud controllers, we use the case/inner-layout
     * layout file. Except submissions.
     *
     * @var string
     */
    protected $pageLayoutInner = 'case/inner-layout';

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'ProhibitionDefect';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_details_prohibitions';

    /**
     * Holds an array of variables for the
     * default index list page.
     */
    protected $listVars = [
        'case',
        'prohibition'
    ];

    /**
     * Data map
     *
     * @var array
     */
    protected $dataMap = array(
        'main' => array(
            'mapFrom' => array(
                'fields',
                'base',
            )
        )
    );

    /**
     * Holds the isAction
     *
     * @var boolean
     */
    protected $isAction = false;

    /**
     * Holds the Data Bundle
     *
     * @var array
     */
    protected $dataBundle = array(
        'children' => array(
            'prohibition' => array(
                'id'
            )
        )
    );

    public function redirectToIndex()
    {
        $prohibition = $this->getFromRoute('prohibition');

        return $this->redirectToRoute(
            'case_prohibition_defect',
            ['action'=>'index', 'prohibition' => $prohibition, 'id' => null],
            ['code' => '303'], // Why? No cache is set with a 303 :)
            true
        );
    }

    public function indexAction()
    {
        $this->forward()->dispatch(
            'CaseProhibitionController',
            array(
                'action' => 'details',
                'case' => $this->getFromRoute('case'),
                'prohibition' => $this->getFromRoute('prohibition')
            )
        );

        //the above call to CaseProhibitionController will have set things like the
        //page title, so we need to reset these as otherwise they will be duplicated
        $this->getViewHelperManager()->get('placeholder')->getContainer('pageTitle')->offsetUnset(1);
        $this->getViewHelperManager()->get('placeholder')->getContainer('pageTitle')->offsetUnset(3);
        $this->getViewHelperManager()->get('placeholder')->getContainer('pageSubtitle')->offsetUnset(1);

        $view = $this->getView([]);

        $this->checkForCrudAction(null, [], $this->getIdentifierName());

        $this->buildTableIntoView();

        $view->setTemplate('prohibition/defect');

        return $this->renderView($view);
    }

    /**
     * Get data for form
     *
     * @return array
     */
    public function getDataForForm()
    {
        $data = parent::getDataForForm();

        $data['fields']['prohibition'] = $this->getFromRoute('prohibition');

        return $data;
    }
}
