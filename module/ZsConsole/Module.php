<?php

namespace ZsConsole;

use ZsConsole\Service\ZendServer,
    Zend\Http\Client as HttpClient,
    Zend\Http\Client\Adapter\Proxy as HttpProxy;

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
                'ZsConsole\HttpClient' => function ($services) {
                    $config = $services->get('config');
                    $config = $config['zsconsole']['http'];

                    $client = new HttpClient();
                    $client->setOptions($config);
                    return $client;
                },
                'ZsConsole\ZendServer' => function($services) {
                    $config = $services->get('config');
                    $config = $config['zsconsole']['servers'];
                    $zs = new Service\ZendServer($config);
                    $zs->setHttpClient($services->get('ZsConsole\HttpClient'));
                    return $zs;
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
