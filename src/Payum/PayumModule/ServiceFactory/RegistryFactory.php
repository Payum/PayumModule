<?php
namespace Payum\PayumModule\ServiceFactory;

use Payum\PayumModule\Registry\ServiceManagerAwareRegistry;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegistryFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        new ServiceManagerAwareRegistry($serviceLocator->get)
    }
}