<?php
namespace Payum\PayumModule\Controller;

use Payum\Core\Request\Notify;

class NotifyController extends PayumController
{
    public function doAction()
    {
        $token = $this->getHttpRequestVerifier()->verify($this);

        $gateway = $this->getPayum()->getGateway($token->getGatewayName());

        $gateway->execute(new Notify($token));

        $this->getResponse()->setStatusCode(204);
    }
}
