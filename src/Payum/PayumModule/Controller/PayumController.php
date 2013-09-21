<?php
namespace Payum\PayumModule\Controller;

use Payum\Security\HttpRequestVerifierInterface;
use Payum\Registry\RegistryInterface;
use Zend\Mvc\Controller\AbstractActionController;

abstract class PayumController extends AbstractActionController
{
    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->serviceLocator->get('payum');
    }

    /**
     * @return HttpRequestVerifierInterface
     */
    protected function getHttpRequestVerifier()
    {
        return $this->serviceLocator->get('payum.security.http_request_verifier');
    }
}