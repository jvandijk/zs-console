<?php

$servers = array();

return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                //'ZendServer' => 'ZsConsole\Service\ZendServer',
            //    'server' => 'ZsConsole\Controller\ServerController',
            ),
            'ZsConsole\Service\ZendServer' => array(
                'parameters' => array(
                    'servers' => $servers,
                )
            ),
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'ZsConsole-servers' => array(
                            'type'    => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route' => '/zs/servers',
                                'defaults' => array(
                                    'controller' => 'ZsConsole\Controller\ServerController',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                        'ZsConsole-server' => array(
                            'type'    => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route' => '/zs/servers/[:serverId]',
                                'defaults' => array(
                                    'controller' => 'ZsConsole\Controller\ServerController',
                                    'action'     => 'server',
                                ),
                            ),
                        ),
                        'ZsConsole-issues' => array(
                            'type'    => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route' => '/zs/servers/[:serverId]/issues',
                                'defaults' => array(
                                    'controller' => 'ZsConsole\Controller\ServerController',
                                    'action'     => 'issues',
                                ),
                            ),
                        ),
                        'ZsConsole-issue' => array(
                            'type' => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route' => '/zs/servers/[:serverId]/issues/[:issueId]',
                                'constraints' => array(
                                    'issueId' => '[0-9]+',
                                ),
                                'defaults' => array(
                                    'controller' => 'ZsConsole\Controller\ServerController',
                                    'action' => 'issue',
                                ),
                            ),
                        ),
                        'ZsConsole-close-issue' => array(
                            'type' => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route' => '/zs/servers/[:serverId]/issues/[:issueId]/close',
                                'constraints' => array(
                                    'issueId' => '[0-9]+',
                                ),
                                'defaults' => array(
                                    'controller' => 'ZsConsole\Controller\ServerController',
                                    'action' => 'closeIssue',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'ZsConsole' => __DIR__ . '/../view',
                    ),
                ),
            ),
        ),
    ),
);
