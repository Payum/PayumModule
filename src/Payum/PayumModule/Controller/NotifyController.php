<?php
namespace Payum\PayumModule\Controller;

use Payum\Core\Request\Notify;

class NotifyController extends PayumController
{
    public function doAction()
    {
        $token = $this->getHttpRequestVerifier()->verify($this->getRequest());

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        $payment->execute(new Notify($token));

        $this->getResponse()->setStatusCode(204);
    }
}