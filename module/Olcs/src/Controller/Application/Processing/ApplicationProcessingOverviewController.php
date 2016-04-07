<?php

/**
 * Overview Controller
 */
namespace Olcs\Controller\Application\Processing;

/**
 * Application Processing Overview Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class ApplicationProcessingOverviewController extends AbstractApplicationProcessingController
{
    protected $section = 'overview';

    public function indexAction()
    {
        $query = $this->getRequest()->getQuery()->toArray();
        $options = [
            'query' => $query
        ];
        return $this->redirectToRoute('lva-application/processing/tasks', [], $options, true);
    }
}
