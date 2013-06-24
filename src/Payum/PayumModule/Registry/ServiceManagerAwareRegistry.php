<?php
namespace Payum\PayumModule\Registry;

use Payum\Registry\AbstractRegistry;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class ServiceManagerAwareRegistry extends AbstractRegistry implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * {@inheritDoc}
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        // TODO: Implement setServiceManager() method.
    }

    /**
     * {@inheritDoc}
     */
    protected function getService($id)
    {
        return $this->serviceManager->get($id);
    }
}