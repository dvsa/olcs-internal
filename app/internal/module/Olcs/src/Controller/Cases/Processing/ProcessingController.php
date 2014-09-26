<?php

/**
 * Processing Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
namespace Olcs\Controller\Cases\Processing;

// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Processing Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
class ProcessingController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Details View
     *
     * @var string
     */
    protected $identifierName = 'case';

    public function overviewAction()
    {
        return $this->redirectToRoute('processing_decisions', ['action' => 'index'], [], true);
    }

    public function redirectToIndex()
    {
        return $this->redirectToRoute('processing_decisions', [], [], true);
    }

    public function detailsAction()
    {
        return $this->redirectToIndex();
    }
}
