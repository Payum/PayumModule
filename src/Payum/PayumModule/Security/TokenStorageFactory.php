<?php
namespace Payum\PayumModule\Security;

use Payum\PayumModule\Options\PayumOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TokenStorageFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('Config');
        $options = new PayumOptions($options['payum']);

        return is_object($options->getTokenStorage()) ?
            $options->getTokenStorage() :
            $serviceLocator->get($options->getTokenStorage())
        ;
    }
}