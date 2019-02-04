<?php
namespace Payum\PayumModule\Options;

use Zend\Stdlib\AbstractOptions;

class PayumOptions extends AbstractOptions
{
    protected $tokenStorage;

    protected $gateways = array();

    protected $storages = array();

    /**
     * @param mixed $tokenStorage
     */
    public function setTokenStorage($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return mixed
     */
    public function getTokenStorage()
    {
        return $this->tokenStorage;
    }

    /**
     * @return array
     */
    public function getGateways()
    {
        return $this->gateways;
    }

    /**
     * @param array $gateways
     */
    public function setGateways(array $gateways)
    {
        $this->gateways = $gateways;
    }

    /**
     * @return array
     */
    public function getStorages()
    {
        return $this->storages;
    }

    /**
     * @param array $storages
     */
    public function setStorages(array $storages)
    {
        $this->storages = $storages;
    }
}