<?php
namespace Payum\PayumModule\Registry;

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
        /** @var PayumOptions $options */
        $options = $serviceLocator->get('payum.options');

        $registry = new ServiceLocatorAwareRegistry(
            $options->getPayments(),
            $options->getStorages(),
            null,
            null
        );
        $registry->registerStorageExtensions();

        return $registry;
    }
}