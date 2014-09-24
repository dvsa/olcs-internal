<?php

/**
 * Bus Details Quality Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Bus\Details;

/**
 * Bus Details Quality Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class BusDetailsQualityController extends BusDetailsController
{
    protected $item = 'quality';

    /* properties required by CrudAbstract */
    protected $formName = 'bus-reg-quality';

    /**
     * Data map
     *
     * @var array
     */
    protected $dataMap = array(
        'main' => array(
            'mapFrom' => array(
                'fields',
            )
        )
    );

    /**
     * Holds the Data Bundle
     *
     * @var array
     */
    protected $dataBundle = array(
        'properties' => 'ALL'
    );

    /**
     * Array of form fields to disable if this is EBSR
     */
    protected $disableFormFields = array(
        'isQualityPartnership',
        'qualityPartnershipDetails',
        'qualityPartnershipFacilitiesUsed',
        'isQualityContract',
        'qualityContractDetails'
    );
}
