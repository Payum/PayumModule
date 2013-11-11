# Get it started.

In this chapter we would show basic setup and usage of payum module for [zf2](http://framework.zend.com/).
We are using paypal here but it could be adopted for any other supported payments.

## Configuration

First add some models to your application:

```php
<?php
namespace Application\Model;

use Payum\Model\ArrayObject;

class PaymentDetails extends \ArrayObject
{
}
```

The other one is `PaymentSecurityToken`.
We will use it to secure our payment operations:

```php
<?php
namespace Application\Model;

use Payum\Model\Token;

class PaymentSecurityToken extends Token
{
}
```

_**Note**: We provide Doctrine ORM\MongoODM mapping for these parent models too.

Add payum module to your application:

```php
<?php
// config/application.php

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

$detailsClass = 'Application\Model\PaymentDetails';

return array(
    'payum' => array(
        'token_storage' => new FilesystemStorage(
            __DIR__.'/../../data',
            'Application\Model\PaymentSecurityToken',
            'hash'
        ),
        'payments' => array(
            'paypal' => PaymentFactory::create(new Api(new Curl(), array(
                'username' => 'REPLACE WITH YOURS',
                'password' => 'REPLACE WITH YOURS',
                'signature' => 'REPLACE WITH YOURS',
                'sandbox' => true
            )))
        ),
        'storages' => array(
            'paypal' => array(
                $detailsClass => new FilesystemStorage(__DIR__.'/../../data', $detailsClass),
            )
        )
    ),
);
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
        $detailsClass = 'Application\Model\PaymentDetails';

        $tokenStorage = $this->getServiceLocator()->get('payum.security.token_storage');
        $storage = $this->getServiceLocator()->get('payum')->getStorageForClass($detailsClass, 'paypal');

        $paymentDetails = $storage->createModel();
        $paymentDetails['PAYMENTREQUEST_0_CURRENCYCODE'] = 'EUR';
        $paymentDetails['PAYMENTREQUEST_0_AMT'] = 1.23;
        $storage->updateModel($paymentDetails);

        $doneToken = $tokenStorage->createModel();
        $doneToken->setPaymentName('paypal');
        $doneToken->setDetails($storage->getIdentificator($paymentDetails));
        $doneToken->setTargetUrl($this->url()->fromRoute(
            'capture_done',
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
            'capture_done' => array(
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
