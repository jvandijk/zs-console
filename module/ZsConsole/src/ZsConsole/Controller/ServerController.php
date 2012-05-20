<?php

namespace ZsConsole\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    ZsConsole\Service\ZendServer;

class ServerController extends ActionController
{
    /**
     *
     * @var ZendServer
     */
    protected $zs = null;

    public function __construct(ZendServer $zs)
    {
        $this->zs = $zs;
    }

    public function indexAction()
    {
        return new ViewModel(array('servers' => $this->zs->getServers()));
    }

    public function serverAction()
    {
        // retrieve param from route match
        $routeMatch = $this->getEvent()->getRouteMatch();
        $serverId = $routeMatch->getParam('serverId');

        $info = $this->zs->systemInfo($serverId);
        return new ViewModel(array('info' => $info, 'server' => $serverId));
    }

    public function issuesAction()
    {
        // retrieve param from route match
        $routeMatch = $this->getEvent()->getRouteMatch();
        $serverId = $routeMatch->getParam('serverId');

        // retrieve param from request
        $request = $this->getEvent()->getRequest();
        $offset = $request->query()->get('offset', 0);
        $limit = $request->query()->get('limit', 10);
        $sort = $request->query()->get('sort', 'date');
        $order = $request->query()->get('order', 'desc');

        $issues = $this->zs->listIssues($serverId, null, $offset, $limit, $sort, $order);

        return new ViewModel(array(
            'issues' => $issues, 'server' => $serverId,
            'offset' => $offset, 'limit' => $limit,
            'sort' => $sort, 'order' => $order,
        ));
    }

    public function issueAction()
    {
        // retrieve param from route match
        $routeMatch = $this->getEvent()->getRouteMatch();
        $serverId = $routeMatch->getParam('serverId');
        $issueId = $routeMatch->getParam('issueId');

        $issue = $this->zs->issueDetails($serverId, $issueId);

        $event = $this->zs->eventDetails($serverId, $issueId, $issue->getEventGroupId());

        return new ViewModel(array('issue' => $issue, 'event' => $event, 'server' => $serverId));
    }

    public function closeIssueAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $serverId = $routeMatch->getParam('serverId');
        $issueId = $routeMatch->getParam('issueId');

        $this->zs->changeIssueStatus($serverId, $issueId);

        $this->redirect()->toUrl($this->getEvent()->getRequest()->server()->get('HTTP_REFERER'));
    }
}
