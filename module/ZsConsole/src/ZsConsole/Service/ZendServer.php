<?php

namespace ZsConsole\Service;

use Zend\Http\Client,
    Zend\Http\Client\Adapter\Proxy,
    Zend\Http\Request,
    Zend\Http\Response,
    ZsConsole\Model\Issue\ResultSet as IssueResultSet,
    ZsConsole\Model\Issue\Entity as IssueEntity,
    ZsConsole\Model\Event\Entity as EventEntity;

class ZendServer
{
    const BASE_API_URI = '/ZendServer/Api/';
    const BASE_API_URI_ZSCM = '/ZendServerManager/Api/';

    protected $client;

    protected $servers = array();

    public function __construct(array $servers)
    {
        $this->setServers($servers);
    }

    public function setServers(array $servers)
    {
        $this->servers = $servers;
        return $this;
    }

    public function getServers()
    {
        return $this->servers;
    }

    public function systemInfo($server)
    {
        $action = 'getSystemInfo';

        $response = $this->prepareHttpClient($server, $action, array())
                         ->send();

        return $this->parseResponse($response);
    }

    public function listIssues($server, $id = null, $offset = 0, $limit = 10, $sort = 'date', $order = 'desc')
    {
        $action = 'monitorGetIssuesListPredefinedFilter';

        if (null === $id) {
            $id = 'All Open Events';
        }

        $params = array(
            'filterId' => $id, 'offset' => $offset, 'limit' => $limit,
            'order' => $sort, 'direction' => strtoupper($order),
        );
        $response = $this->prepareHttpClient($server, $action, $params)
                         ->send();

        $result = new IssueResultSet();
        return $result->setData($this->parseResponse($response));
    }

    public function issueDetails($server, $id)
    {
        $action = 'monitorGetIssueDetails';

        $response = $this->prepareHttpClient($server, $action, array('issueId' => $id))
                         ->send();


        $result = new IssueEntity();
        $result->setXml($this->parseResponse($response));

        return $result;
    }

    public function eventDetails($server, $id, $eventGroupId)
    {
        $action = 'monitorGetEventGroupDetails';

        $response = $this->prepareHttpClient($server, $action, array('issueId' => $id, 'eventGroupId' => $eventGroupId))
                         ->send();

        $result = new EventEntity();
        $result->setXml($this->parseResponse($response));

        return $result;
    }

    public function changeIssueStatus($server, $id, $status = 'Closed')
    {
        $action = 'monitorChangeIssueStatus';

        $response = $this->prepareHttpClient($server, $action, array('issueId' => $id, 'newStatus' => $status), Request::METHOD_POST)
                         ->send();

        $result = new IssueEntity();
        $result->setXml($this->parseResponse($response));

        return $result;
    }

    /**
     *
     * @return Client
     */
    public function getHttpClient()
    {
        if (null === $this->client) {
            $adapter = new Proxy();
            $config = array(
                'proxy_host' => '192.168.56.1',
                'proxy_port' => 8888);
            $this->client = new Client();
            $this->client->setAdapter($adapter);
            //$this->client->setConfig($config);

            $this->client->getRequest()
                         ->headers();
        }

        return $this->client;
    }

    public function setHttpClient(Client $client)
    {
        $this->client = $client;
    }

    protected function prepareHttpClient($server, $path, array $data = array(), $method = Request::METHOD_GET)
    {
        $timestamp = time();

        $base = (isset($server['cluster'])) ? self::BASE_API_URI_ZSCM : self::BASE_API_URI;

        $sig = $this->generateRequestSignature(
                $this->servers[$server]['host'], $base . $path,
                $timestamp, 'Zend\Http\Client', $this->servers[$server]['user'],
                $this->servers[$server]['apikey']);

        $this->getHttpClient()
             ->setUri('http://'.$this->servers[$server]['host'].$base . $path)
             ->setHeaders(array(
                 'X-Zend-Signature' => $sig,
                 'Accept' => 'application/vnd.zend.serverapi+xml;version=1.2',
                 'Date' => gmdate('D, d M y H:i:s ', $timestamp) . 'GMT'
             ));

        switch ($method) {
            case Request::METHOD_GET:
                $this->getHttpClient()
                     ->setMethod(Request::METHOD_GET)
                     ->setParameterGet($data);
                break;
            case Request::METHOD_POST:
                $this->getHttpClient()
                     ->setMethod(Request::METHOD_POST)
                     ->setParameterPost($data);
        }

        return $this->getHttpClient();
    }

    protected function parseResponse(Response $response)
    {
        $body = $response->getBody();

        return $body;

        if (!$response->isOk()) {
            if ('ok' !== $body->response->status) {
                throw new RuntimeException(sprintf(
                        'Could not send request: api error "%s" (%s)',
                        $body->response->status,
                        $body->response->message));
            } else {
                throw new RuntimeException('Unknown error during request to Postage server');
            }
        }

        return $body->data;
    }

    /**
     * Calculate Zend Server Web API request signature
     *
     * @param string $host Exact value of the 'Host:' HTTP header
     * @param string $path Request URI
     * @param integer $timestamp Timestamp used for the 'Date:' HTTP header
     * @param string $userAgent Exact value of the 'User-Agent:' HTTP header
     * @param string $apiKey Zend Server API key
     * @return string Calculated request signature
     */
    protected function generateRequestSignature($host, $path, $timestamp, $userAgent, $user, $apiKey)
    {
        $data = $host . ":" .
                $path . ":" .
                $userAgent . ":" .
                gmdate('D, d M y H:i:s ', $timestamp) . 'GMT';

        return $user.'; '.hash_hmac('sha256', $data, $apiKey);
    }
}