<?php

namespace Olcs\Listener\RouteParam;

use Common\Service\Cqrs\Command\CommandSenderAwareInterface;
use Common\Service\Cqrs\Command\CommandSenderAwareTrait;
use Common\Service\Cqrs\Query\QuerySenderAwareInterface;
use Common\Service\Cqrs\Query\QuerySenderAwareTrait;
use Dvsa\Olcs\Transfer\Command\Audit\ReadCase;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use \Dvsa\Olcs\Transfer\Query\Cases\Cases as ItemDto;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;
use Common\Exception\ResourceNotFoundException;
use Zend\View\Helper\Url;
use Zend\View\Model\ViewModel;

/**
 * Submissions Furniture
 *
 * @author Shaun Lizzio <shaun@lizzio.co.uk>
 */
class SubmissionsFurniture implements
    ListenerAggregateInterface,
    FactoryInterface,
    QuerySenderAwareInterface,
    CommandSenderAwareInterface
{
    use ListenerAggregateTrait,
        ViewHelperManagerAwareTrait,
        QuerySenderAwareTrait,
        CommandSenderAwareTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setQuerySender($serviceLocator->get('QuerySender'));
        $this->setCommandSender($serviceLocator->get('CommandSender'));
        $this->setViewHelperManager($serviceLocator->get('ViewHelperManager'));

        return $this;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(RouteParams::EVENT_PARAM . 'case', [$this, 'onSubmission'], 1);
    }

    /**
     * @param RouteParam $e
     */
    public function onSubmission(RouteParam $e)
    {
        $id = $e->getValue();

        $this->getCommandSender()->send(ReadCase::create(['id' => $id]));

        $case = $this->getCase($id);

        $placeholder = $this->getViewHelperManager()->get('placeholder');
        $placeholder->getContainer('pageTitle')->set($this->getPageTitle($case));
        $placeholder->getContainer('status')->set($this->getStatusArray($case));
        $placeholder->getContainer('horizontalNavigationId')->set('case');

        // no right view
    }

    /**
     * Get the Case data
     *
     * @param int $id
     * @return array
     * @throws ResourceNotFoundException
     */
    private function getCase($id)
    {
        $response = $this->getQuerySender()->send(
            ItemDto::create(['id' => $id])
        );

        if (!$response->isOk()) {
            throw new ResourceNotFoundException("Case id [$id] not found");
        }

        return $response->getResult();
    }

    /**
     * Returns page title
     *
     * @param $case
     * @return string
     */
    private function getPageTitle($case)
    {
        $pageTitle = 'Case ' . $case['id'];

        /** @var Url $urlHelper */
        $urlHelper = $this->getViewHelperManager()->get('url');

        if (isset($case['application']['id'])) {
            // prepend with application link
            $appUrl = $urlHelper('lva-application/case', ['application' => $case['application']['id']], [], true);

            $pageTitle = sprintf('<a href="%1$s">%2$s</a> / %3$s', $appUrl, $case['application']['id'], $pageTitle);
        }

        if (isset($case['licence']['id'])) {
            // prepend with licence link
            $licUrl = $urlHelper('licence/cases', ['licence' => $case['licence']['id']], [], true);

            $pageTitle = sprintf('<a href="%1$s">%2$s</a> / %3$s', $licUrl, $case['licence']['licNo'], $pageTitle);
        }

        if (isset($case['transportManager']['id'])) {
            $url = $urlHelper(
                'transport-manager/details',
                ['transportManager' => $case['transportManager']['id']],
                [],
                true
            );

            $pageTitle = sprintf(
                '<a href="%s">%s %s</a> / %s',
                $url,
                $case['transportManager']['homeCd']['person']['forename'],
                $case['transportManager']['homeCd']['person']['familyName'],
                $pageTitle
            );
        }

        return $pageTitle;
    }

    /**
     * Get status array.
     *
     * @param $case
     *
     * @return array
     */
    private function getStatusArray($case)
    {
        $status = [
            'colour' => isset($case['closedDate']) ? 'grey' : 'orange',
            'value' => isset($case['closedDate']) ? 'Closed' : 'Open',
        ];

        return $status;
    }
}
