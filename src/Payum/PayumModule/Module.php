<?php
namespace Payum\PayumModule;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface
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
        return array(
            'factories' => array(
                'payum' => 'Payum\PayumModule\Service\RegistryFactory',
                'payum.security.token_storage' => 'Payum\PayumModule\Service\TokenStorageFactory',
                'payum.security.http_request_verifier' => 'Payum\PayumModule\Service\HttpRequestVerifierFactory',
            ),
        );
    }
}