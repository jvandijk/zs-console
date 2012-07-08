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
                        'zs-console' => array(
                            'type'    => 'Literal',
                            'options' => array(
                                'route' => '/zs/servers',
                                'defaults' => array(
                                    'controller' => 'ZsConsole\Controller\ServerController',
                                    'action'     => 'index',
                                ),
                            ),
                            'may_terminate' => true,
                            'child_routes' => array(
                                'server' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                        'route' => '/[:serverId]',
                                        'defaults' => array(
                                            'action'     => 'server',
                                        ),
                                    ),
                                ),
                                'issues' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                        'route' => '/[:serverId]/issues',
                                        'defaults' => array(
                                            'action'     => 'issues',
                                        ),
                                    ),
                                ),
                                'issue' => array(
                                    'type' => 'Segment',
                                    'options' => array(
                                        'route' => '/[:serverId]/issues/[:issueId]',
                                        'constraints' => array(
                                            'issueId' => '[0-9]+',
                                        ),
                                        'defaults' => array(
                                            'action' => 'issue',
                                        ),
                                    ),
                                ),
                                'close-issue' => array(
                                    'type' => 'Segment',
                                    'options' => array(
                                        'route' => '/[:serverId]/issues/[:issueId]/close',
                                        'constraints' => array(
                                            'issueId' => '[0-9]+',
                                        ),
                                        'defaults' => array(
                                            'action' => 'closeIssue',
                                        ),
                                    ),
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
