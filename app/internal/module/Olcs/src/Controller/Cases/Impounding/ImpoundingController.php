<?php

/**
 * Case Impounding Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Cases\Impounding;

// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Impounding Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class ImpoundingController extends OlcsController\CrudAbstract
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
    protected $tableName = 'impounding';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'impounding';

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
    protected $service = 'Impounding';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_details_impounding';

    /**
     * Holds an array of variables for the
     * default index list page.
     */
    protected $listVars = [
        'case',
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
            'case' => array(
                'properties' => array(
                    'id'
                )
            ),
            'presidingTc' => array(
                'properties' => array(
                    'id',
                    'name'
                )
            ),
            'outcome' => array(
                'properties' => array(
                    'id',
                    'name'
                )
            ),
            'impoundingType' => array(
                'properties' => array(
                    'id',
                    'description'
                )
            ),
            'piVenue' => array(
                'properties' => array(
                    'id',
                    'name'
                )
            ),
            'impoundingLegislationTypes' => array(
                'properties' => 'ALL'
            ),
        )
    );

    /**
     * Any inline scripts needed in this section
     *
     * @var array
     */
    protected $inlineScripts = array('forms/impounding');

    /**
     * @codeCoverageIgnore This method is to assist with unit testing
     *
     * @param string $name
     * @param callable $callback
     * @param mixed $data
     * @param boolean $tables
     * @return object
     */
    public function callParentGenerateFormWithData($name, $callback, $data = null, $tables = false)
    {
        return parent::generateFormWithData($name, $callback, $data, $tables);
    }

    /**
     * Overrides the parent so that hearing location can be processed properly
     *
     * @param string $name
     * @param callable $callback
     * @param mixed $data
     * @param boolean $tables
     * @return object
     */
    public function generateFormWithData($name, $callback, $data = null, $tables = false)
    {
        $form = $this->callParentGenerateFormWithData($name, $callback, $data, $tables);

        $fields = $form->get('fields');

        $piVenue = $fields->get('piVenue')->getValue();
        $piVenueOther = $fields->get('piVenueOther')->getValue();

        //second check not strictly necessary but would mean the piVenue
        //field would have priority if both fields somehow had data
        if (!empty($piVenueOther) && empty($piVenue)) {
            $fields->get('piVenue')->setValue('other');
        }

        return $form;
    }

    /**
     * Overrides the parent, needed to make absolutely sure we can't have data in both venue fields :)
     *
     * @param array $data
     * @return \Zend\Http\Response
     */
    public function processSave($data)
    {
        if ($data['fields']['piVenue'] != 'other') {
            $data['fields']['piVenueOther'] = null;
        }

        return parent::processSave($data);
    }
}
