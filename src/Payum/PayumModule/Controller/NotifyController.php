<?php
namespace Payum\PayumModule\Controller;

use Payum\Core\Request\SecuredNotifyRequest;

class NotifyController extends PayumController
{
    public function doAction()
    {
        $token = $this->getHttpRequestVerifier()->verify($this->getRequest());

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        $payment->execute(new SecuredNotifyRequest($_REQUEST, $token));

        $this->getResponse()->setStatusCode(204);
    }
}