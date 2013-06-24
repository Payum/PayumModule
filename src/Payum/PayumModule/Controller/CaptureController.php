<?php
namespace Payum\PayumModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\ViewModel;

use Payum\PayumModule\Service\TokenManager;
use Payum\Registry\RegistryInterface;
use Payum\Request\BinaryMaskStatusRequest;

class CaptureController extends AbstractActionController
{
    public function doAction()
    {
        $token = $this->getTokenManager()->getTokenFromRequest($this->getRequest());

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        $status = new BinaryMaskStatusRequest($token);
        $payment->execute($status);
        if (false == $status->isNew()) {
            $this->getResponse()->setStatusCode(400);
            
            return new ViewModel(array(
                'content' => 'The model status must be new.',
            ));
        }

        $capture = new CaptureTokenizedDetailsRequest($token);
        $payment->execute($capture);

        $this->getTokenManager()->deleteToken($token);

        return $this->redirect($token->getAfterUrl());
    }

    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->serviceLocator->get('payum');
    }

    /**
     * @return TokenManager
     */
    protected function getTokenManager()
    {
        return $this->serviceLocator->get('payum.token_manager');
    }
}