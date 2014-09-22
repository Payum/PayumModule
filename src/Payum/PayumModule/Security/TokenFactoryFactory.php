<?php
namespace Payum\PayumModule\Security;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TokenFactoryFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TokenFactory(
            $serviceLocator->get('payum.security.token_storage'),
            $serviceLocator->get('payum'),
            'payum_capture_do',
            'payum_notify_do',
            'payum_authorize_do'
        );
    }
}