<?php
namespace Payum\PayumModule\Security;

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
        return new HttpRequestVerifier(
            $serviceLocator->get('payum.security.token_storage')
        );
    }
}