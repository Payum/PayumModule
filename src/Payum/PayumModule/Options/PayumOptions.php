<?php
namespace Payum\PayumModule\Options;

use Zend\Stdlib\AbstractOptions;

class PayumOptions extends AbstractOptions
{
    protected $tokenStorage;

    protected $payments = array();

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
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param array $payments
     */
    public function setPayments(array $payments)
    {
        $this->payments = $payments;
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