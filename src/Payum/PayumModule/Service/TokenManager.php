<?php
namespace Payum\PayumModule\Service;

use Payum\Exception\LogicException;
use Payum\Model\TokenizedDetails;
use Payum\Storage\StorageInterface;

use Zend\Http\Request;

class TokenManager
{
    /**
     * @param Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return \Payum\Model\TokenizedDetails
     */
    public function getTokenFromRequest(Request $request)
    {
        //TODO
    }

    /**
     * @param string $paymentName
     * @param object $model
     * @param string $afterRoute
     * @param array $afterRouteParameters
     *
     * @return TokenizedDetails
     */
    public function createTokenForCaptureRoute($paymentName, $model, $afterRoute, array $afterRouteParameters = array())
    {
        //TODO
    }

    /**
     * @param string $paymentName
     * @param object $model
     * @param string $targetRoute
     * @param array $targetRouteParameters
     * @param string $afterRoute
     * @param array $afterRouteParameters
     *
     * @return TokenizedDetails
     */
    public function createTokenForRoute($paymentName, $model, $targetRoute, array $targetRouteParameters = array(), $afterRoute = null, array $afterRouteParameters = array())
    {
        //TODO
    }

    /**
     * @param string $paymentName
     * @param string $token
     *
     * @return TokenizedDetails
     */
    public function findByToken($paymentName, $token)
    {
        //TODO
    }

    /**
     * @param TokenizedDetails $token
     */
    public function deleteToken(TokenizedDetails $token)
    {
        //TODO
    }

    /**
     * @param string $paymentName
     *
     * @throws LogicException when storage for TokenizedDetails instance not found
     *
     * @return StorageInterface
     */
    public function getStorage($paymentName)
    {
        //TODO
    }
}