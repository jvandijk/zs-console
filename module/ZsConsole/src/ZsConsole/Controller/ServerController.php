<?php

namespace ZsConsole\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    ZsConsole\Service\ZendServer as ZendServer;

class ServerController extends AbstractActionController
{
    /**
     *
     * @var ZendServer
     */
    protected $zs = null;

    public function setZendServerService(ZendServer $zs)
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
        $offset = $request->getQuery()->get('offset', 0);
        $limit = $request->getQuery()->get('limit', 10);
        $sort = $request->getQuery()->get('sort', 'date');
        $order = $request->getQuery()->get('order', 'desc');

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

        $this->redirect()->toUrl($this->getEvent()->getRequest()->getServer()->get('HTTP_REFERER'));
    }
}
