<?php

/**
 * Case Conviction Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */

namespace Olcs\Controller\Cases\Conviction;

use Zend\View\Model\ViewModel;
use Zend\Json\Json as Json;
//use Olcs\Controller\Traits\DeleteActionTrait;
use Olcs\Controller\Traits\DefendantSearchTrait;
// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Conviction Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class ConvictionController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'conviction';

    /**
     * Table name string
     *
     * @var string
     */
    protected $tableName = 'conviction';

    /**
     * Name of comment box field.
     *
     * @var string
     */
    protected $commentBoxName = 'convictionNote';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'conviction';

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case';

    protected $pageLayoutInner = 'case/inner-layout';

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'Conviction';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represneted by a single navigation id.
     */
    protected $navigationId = 'case_details_convictions';

    /**
     * Holds an array of variables for the default
     * index list page.
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
                'fields'
            )
        )
    );

    /**
     * Holds the Data Bundle
     *
     * @var array
    */
    protected $dataBundle = array(
        'children' => array(
            'case' => array(
                'properties' => 'ALL'
            ),
            'convictionCategory' => array(
                'properties' => array(
                    'id',
                    'description'
                ),
                'children' => array(
                    'parent' => array(
                        'properties' => array(
                            'id',
                            'description'
                        )
                    )
                )
            ),
            'defendantType' => array(
                'properties' => 'ALL'
            )
        )
    );

    /**
     * @var array
     */
    protected $inlineScripts = ['showhideinput', 'conviction'];
}
