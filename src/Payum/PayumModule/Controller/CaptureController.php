<?php
namespace Payum\PayumModule\Controller;

use Payum\Core\Request\RedirectUrlInteractiveRequest;
use Payum\Core\Request\SecuredCaptureRequest;
use Payum\Core\Request\BinaryMaskStatusRequest;

class CaptureController extends PayumController
{
    public function doAction()
    {
        $token = $this->getHttpRequestVerifier()->verify($this->getRequest());

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        $status = new BinaryMaskStatusRequest($token);
        $payment->execute($status);
        if (false == $status->isNew()) {
            $this->getResponse()->setStatusCode(400);
            return;
        }

        try {
            $capture = new SecuredCaptureRequest($token);
            $payment->execute($capture);
        } catch (RedirectUrlInteractiveRequest $interactiveRequest) {
            $this->redirect()->toUrl($interactiveRequest->getUrl());
            return;
        }

        $this->getHttpRequestVerifier()->invalidate($token);

        $this->redirect()->toUrl($token->getAfterUrl());
    }
}