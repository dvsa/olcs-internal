<?php

/**
 * Transport Manager Processing History Controller
 * 
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Controller\TransportManager\Processing;

use Olcs\Controller\TransportManager\Processing\AbstractTransportManagerProcessingController;

/**
 * Transport Manager Processing History Controller
 * 
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class HistoryController extends AbstractTransportManagerProcessingController
{
    /**
     * @var string
     */
    protected $section = 'processing-history';

    /**
     * Placeholder stub
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $view = $this->getViewWithTm();

        $params = [
            'transportManager' => $this->getQueryOrRouteParam('transportManager'),
            'page'    => $this->getQueryOrRouteParam('page', 1),
            'sort'    => $this->getQueryOrRouteParam('sort', 'eventDatetime'),
            'order'   => $this->getQueryOrRouteParam('order', 'DESC'),
            'limit'   => $this->getQueryOrRouteParam('limit', 10),
        ];

        $params['query'] = $this->getRequest()->getQuery()->toArray();

        $bundle = array(
            'children' => array(
                'eventHistoryType' => [],
                'user' => [
                    'children' => [
                        'contactDetails' => [
                            'children' => [
                                'person' => [],
                            ]
                        ]
                    ]
                ]
            )
        );

        $results = $this->makeRestCall('EventHistory', 'GET', $params, $bundle);

        $view->{'table'} = $this->getTable('event-history', $results, $params);

        $view->setTemplate('partials/table');
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());

        return $this->renderView($view);
    }
}
