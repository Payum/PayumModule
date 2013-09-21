<?php
namespace Payum\PayumModule\Service;

use Payum\PayumModule\Options\PayumOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HttpRequestVerifierFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new HttpRequestVerifierFactory(
            $serviceLocator->get('payum.security.token_storage')
        );
    }
}