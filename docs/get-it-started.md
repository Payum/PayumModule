# Get it started.

I am using paypal here but it could be adopted for any other supported payments.

## Configure

Add payum module to your application:

```php
<?php
// config/application.php
<?php
return array(
    'modules' => array(
        'Payum\PayumModule'
    ),
);
```

Configure payum module:

```php
<?php
// config/autoload/global.php

use Buzz\Client\Curl;
use Payum\Extension\StorageExtension;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Paypal\ExpressCheckout\Nvp\PaymentFactory;
use Payum\Storage\FilesystemStorage;

$paypalPayment = PaymentFactory::create(new Api(new Curl(), array(
    'username' => 'testrj_1312968849_biz_api1.remixjobs.com',
    'password' => '1312968888',
    'signature' => 'Azgw.f7NYjBAlDQEpbI1D06D4ACAAXfoVSV7k4JUuGAPRHzhDbQR2r90',
    'sandbox' => true
)));

$paypalPaymentDetailsStorage = new FilesystemStorage(
    __DIR__.'/../../data',
    'Application\Model\PaypalPaymentDetails',
    'id'
);

$paypalPayment->addExtension(new StorageExtension($paypalPaymentDetailsStorage));

return array(
    'payum' => array(
        'token_storage' => new FilesystemStorage(
            __DIR__.'/../../data',
            'Payum\Model\Token',
            'hash'
        ),
        'payments' => array(
            'paypal' => $paypalPayment
        ),
        'storages' => array(
            'paypal' => array(
                'Application\Model\PaypalPaymentDetails' => $paypalPaymentDetailsStorage,
            )
        )
    ),
);
```

Extend paypal payment details model:

```php
<?php
// module/Application/src/Application/Model/PaypalPaymentDetails.php

namespace Application\Model;

use Payum\Paypal\ExpressCheckout\Nvp\Model\PaymentDetails;

class PaypalPaymentDetails extends  PaymentDetails
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
```

## Prepare paypal payment.

```php
<?php
namespace Application\Controller;

use Payum\Request\BinaryMaskStatusRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocator;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $tokenStorage = $this->getServiceLocator()->get('payum.security.token_storage');
        $storage = $this->getServiceLocator()->get('payum')->getStorageForClass('Application\Model\PaypalPaymentDetails', 'paypal');

        $paymentDetails = $storage->createModel();
        $paymentDetails['PAYMENTREQUEST_0_CURRENCYCODE'] = 'EUR';
        $paymentDetails['PAYMENTREQUEST_0_AMT'] = 1.23;
        $storage->updateModel($paymentDetails);

        $doneToken = $tokenStorage->createModel();
        $doneToken->setPaymentName('paypal');
        $doneToken->setDetails($storage->getIdentificator($paymentDetails));
        $doneToken->setTargetUrl($this->url()->fromRoute(
            'payment_details',
            array(),
            array('force_canonical' => true, 'query' => array('payum_token' => $doneToken->getHash()))
        ));
        $tokenStorage->updateModel($doneToken);

        $captureToken = $tokenStorage->createModel();
        $captureToken->setPaymentName('paypal');
        $captureToken->setDetails($storage->getIdentificator($paymentDetails));
        $captureToken->setTargetUrl($this->url()->fromRoute(
            'payum_capture_do',
            array(),
            array('force_canonical' => true, 'query' => array('payum_token' => $captureToken->getHash()))
        ));
        $captureToken->setAfterUrl($doneToken->getTargetUrl());
        $tokenStorage->updateModel($captureToken);

        $paymentDetails['RETURNURL'] = $captureToken->getTargetUrl();
        $paymentDetails['CANCELURL'] = $captureToken->getTargetUrl();
        $storage->updateModel($paymentDetails);

        $this->redirect()->toUrl($captureToken->getTargetUrl());
    }
}
```

## Done action

We will be redirected to it after the payment is done. Let's start from action:

```php
<?php
namespace Application\Controller;

use Payum\Request\BinaryMaskStatusRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocator;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function doneAction()
    {
        $token = $this->getServiceLocator()->get('payum.security.http_request_verifier')->verify($this->getRequest());

        $payment = $this->getServiceLocator()->get('payum')->getPayment($token->getPaymentName());

        $payment->execute($status = new BinaryMaskStatusRequest($token));

        var_dump(json_encode(array('status' => $status->getStatus()) + iterator_to_array($status->getModel()), JSON_PRETTY_PRINT));
        die;
    }
}
```

and route for it:

```php
<?php
// module/Application/config/module.config.php

return array(
    'router' => array(
        'routes' => array(
            'done' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/done[/:payum_token]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'done',
                    ),
                ),
            ),
        ),
    ),
);
```


Back to [index](index.md).