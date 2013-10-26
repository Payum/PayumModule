<?php

namespace Payum\PayumModule\Controller;

use Payum\Registry\RegistryInterface;
use Payum\Security\HttpRequestVerifierInterface;
use Zend\Mvc\Controller\AbstractActionController;

abstract class PayumController extends AbstractActionController
{
    /**
     * @var RegistryInterface
     */
    protected $payumRegistry;

    /**
     * @var HttpRequestVerifierInterface
     */
    protected $httpRequestVerifier;



    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->payumRegistry;
    }

    /**
     * @param RegistryInterface $payumRegistry
     * @return \Payum\PayumModule\Controller\PayumController
     */
    public function setPayum(RegistryInterface $payumRegistry)
    {
        $this->payumRegistry = $payumRegistry;

        // Fluent interface.
        return $this;
    }

    /**
     * @return HttpRequestVerifierInterface
     */
    protected function getHttpRequestVerifier()
    {
        return $this->httpRequestVerifier;
    }

    /**
     * @param HttpRequestVerifierInterface $httpRequestVerifier
     * @return \Payum\PayumModule\Controller\PayumController
     */
    public function setHttpRequestVerifier(HttpRequestVerifierInterface $httpRequestVerifier)
    {
        $this->httpRequestVerifier = $httpRequestVerifier;

        // Fluent interface.
        return $this;
    }
}
