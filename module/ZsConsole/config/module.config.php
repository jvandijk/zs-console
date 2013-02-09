<?php

return array(
    'zsconsole' => array(
        // configure servers as follows in your local config file
        'servers' => array(
        //  '<unique-url-name>' => array(
        //      'name' => 'ID',
        //      'host' => '127.0.0.1:10081',
        //      'user' => '',
        //      'apikey' => '',
        //      'cluster' => false,
        //  ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'zs-console' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route' => '/zs/servers',
                    'defaults' => array(
                        'controller' => 'ZsConsole\Controller\Server',
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
                                'action' => 'server',
                            ),
                        ),
                    ),
                    'issues' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/[:serverId]/issues',
                            'defaults' => array(
                                'action' => 'issues',
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
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);
