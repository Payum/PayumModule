<?php
namespace Payum\PayumModule\Service;

use Payum\PayumModule\Options\PayumOptions;
use Payum\PayumModule\Registry\ServiceLocatorAwareRegistry;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegistryFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('Config');
        $options = new PayumOptions($options['payum']);

        $registry = new ServiceLocatorAwareRegistry($options->getPayments(), $options->getStorages(), null, null);
        $registry->setServiceLocator($serviceLocator);

        return $registry;
    }
}