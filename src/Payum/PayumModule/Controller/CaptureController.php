<?php
namespace Payum\PayumModule\Controller;

use Payum\Core\Request\Http\RedirectUrlInteractiveRequest;
use Payum\Core\Request\Http\ResponseInteractiveRequest;
use Payum\Core\Request\InteractiveRequestInterface;
use Payum\Core\Request\SecuredCaptureRequest;
use Zend\Http\Response;

class CaptureController extends PayumController
{
    public function doAction()
    {
        $token = $this->getHttpRequestVerifier()->verify($this->getRequest());

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        try {
            $payment->execute(new SecuredCaptureRequest($token));
        } catch (InteractiveRequestInterface $interactiveRequest) {
            if ($interactiveRequest instanceof RedirectUrlInteractiveRequest) {
                $this->redirect()->toUrl($interactiveRequest->getUrl());
            }

            if ($interactiveRequest instanceof ResponseInteractiveRequest) {
                $this->getResponse()->setContent($interactiveRequest->getContent());

                $response = new Response();
                $response->setStatusCode(200);
                $response->setContent($interactiveRequest->getContent());

                return $response;
            }

            throw new \LogicException('Unsupported interactive request', null, $interactiveRequest);
        }

        $this->getHttpRequestVerifier()->invalidate($token);

        $this->redirect()->toUrl($token->getAfterUrl());
    }
}