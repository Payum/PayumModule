<?php
namespace Payum\PayumModule;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\RouteProviderInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface, RouteProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/../../../config/services.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteConfig()
    {
        return include __DIR__ . '/../../../config/routes.config.php';
    }
}