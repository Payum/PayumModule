<?php

namespace Payum\PayumModule\Controller;

use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Security\HttpRequestVerifierInterface;
use Zend\Mvc\Controller\AbstractActionController;

abstract class PayumController extends AbstractActionController
{
    /**
     * @var RegistryInterface
     */
    protected $payum;

    /**
     * @var HttpRequestVerifierInterface
     */
    protected $httpRequestVerifier;

    /**
     * Create Payum Controller using required dependencies from parameters.
     *
     * @param RegistryInterface $payum
     * @param HttpRequestVerifierInterface $httpRequestVerifier
     */
    public function __construct(RegistryInterface $payum, HttpRequestVerifierInterface $httpRequestVerifier)
    {
        $this->payum = $payum;
        $this->httpRequestVerifier = $httpRequestVerifier;
    }

    /**
     * Retrieve the Payum Registry.
     *
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->payum;
    }

    /**
     * Retrieve the Payum HttpRequestVerifier.
     *
     * @return HttpRequestVerifierInterface
     */
    protected function getHttpRequestVerifier()
    {
        return $this->httpRequestVerifier;
    }
}
