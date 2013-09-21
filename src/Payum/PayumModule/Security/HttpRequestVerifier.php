<?php
namespace Payum\PayumModule\Security;

use Payum\Exception\InvalidArgumentException;
use Payum\Model\Token;
use Payum\Security\HttpRequestVerifierInterface;
use Payum\Security\TokenInterface;
use Payum\Storage\StorageInterface;
use Zend\Http\Request;

class HttpRequestVerifier implements HttpRequestVerifierInterface
{
    /**
     * @var \Payum\Storage\StorageInterface
     */
    protected $tokenStorage;

    /**
     * @param StorageInterface $tokenStorage
     */
    public function __construct(StorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function verify($httpRequest)
    {
        if (false == $httpRequest instanceof Request) {
            throw new InvalidArgumentException(sprintf(
                'Invalid request given. Expected %s but it is %s',
                'Zend\Http\Request',
                is_object($httpRequest) ? get_class($httpRequest) : gettype($httpRequest)
            ));
        }

        /** @var $httpRequest Request */
        if (false === $hash = $httpRequest->getMetadata('payum_token', $httpRequest->getQuery('payum_token'))) {
            //TODO we should set 404 to response but I do not know how. symfony just throws not found exception.
            throw new InvalidArgumentException('Token parameter not set in request');
        }

        if ($hash instanceof Token) {
            $token = $hash;
        } else {
            if (false == $token = $this->tokenStorage->findModelById($hash)) {
                //TODO here again should be 404
                throw new InvalidArgumentException(sprintf('A token with hash `%s` could not be found.', $hash));
            }

            if ($httpRequest->getUri()->getPath() != parse_url($token->getTargetUrl(), PHP_URL_PATH)) {
                //TODO here again should be 400
                throw new InvalidArgumentException('The current url %s not match target url %s set in the token.', $httpRequest->getUri()->getPath(), $token->getTargetUrl());
            }
        }

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function invalidate(TokenInterface $token)
    {
        $this->tokenStorage->deleteModel($token);
    }
}