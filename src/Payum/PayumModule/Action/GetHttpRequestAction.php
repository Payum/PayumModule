<?php
namespace Payum\PayumModule\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Http\GetRequestRequest;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceLocatorInterface;

class GetHttpRequestAction implements ActionInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request GetRequestRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        /** @var Request $httpRequest */
        $httpRequest = $this->serviceLocator->get('Request');

        $request->query = $httpRequest->getQuery()->toArray();
        $request->request = $httpRequest->getPost()->toArray();
        $request->method = $httpRequest->getMethod();
        $request->uri = $httpRequest->getUriString();
        $request->clientIp = $httpRequest->getServer('REMOTE_ADDR');
        $request->userAgent = $httpRequest->getHeader('User-Agent');
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof GetRequestRequest;
    }
}