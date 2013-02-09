<?php

namespace ZsConsole;

use ZsConsole\Service\ZendServer;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ZsConsole\ZendServer' => function($services) {
                    $config = $services->get('config');
                    $config = $config['zsconsole']['servers'];
                    return new Service\ZendServer($config);
                },
            )
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'ZsConsole\Controller\Server' => function($controllers) {
                    $services     = $controllers->getServiceLocator();
                    $zendServer   = $services->get('ZsConsole\ZendServer');

                    $controller = new Controller\ServerController();
                    $controller->setZendServerService($zendServer);

                    return $controller;
                },
            ),
        );
    }
}
