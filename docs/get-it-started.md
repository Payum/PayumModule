# Get it started.

In this chapter we would show basic setup and usage of payum module for [zf2](http://framework.zend.com/).
We are using paypal here but it could be adopted for any other supported payments.

## Installation

```bash
php composer.phar require "payum/payum-module:*@stable" "payum/xxx:*@stable"
```

_**Note**: Where payum/xxx is a payum package, for example it could be payum/paypal-express-checkout-nvp. Look at [supported payments](https://github.com/Payum/Core/blob/master/Resources/docs/supported-payments.md) to find out what you can use._

_**Note**: Use payum/payum if you want to install all payments at once._

Now you have all codes prepared and ready to be used.

## Configuration

First add some models to your application:

```php
<?php
namespace Application\Model;

use Payum\Core\Model\ArrayObject;

class PaymentDetails extends \ArrayObject
{
    protected $id;
}
```

The other one is `PaymentSecurityToken`.
We will use it to secure our payment operations:

```php
<?php
namespace Application\Model;

use Payum\Core\Model\Token;

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
use Payum\Core\Extension\StorageExtension;
use Payum\Core\Storage\FilesystemStorage;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Paypal\ExpressCheckout\Nvp\PaymentFactory;

$detailsClass = 'Application\Model\PaymentDetails';

return array(
    'payum' => array(
        'token_storage' => new FilesystemStorage(
            __DIR__.'/../../data',
            'Application\Model\PaymentSecurityToken',
            'hash'
        ),
        'payments' => array(
            'paypal_ec' => PaymentFactory::create(new Api(new Curl(), array(
                'username' => 'REPLACE WITH YOURS',
                'password' => 'REPLACE WITH YOURS',
                'signature' => 'REPLACE WITH YOURS',
                'sandbox' => true
            )))
        ),
        'storages' => array(
            $detailsClass => new FilesystemStorage(__DIR__.'/../../data', $detailsClass, 'id'),
        )
    ),
);
```

## Prepare paypal payment.

```php
<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocator;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $storage = $this->getServiceLocator()->get('payum')->getStorage('Application\Model\PaymentDetails');

        $details = $storage->create();
        $details['PAYMENTREQUEST_0_CURRENCYCODE'] = 'EUR';
        $details['PAYMENTREQUEST_0_AMT'] = 1.23;
        $storage->update($details);

        // FIXIT: I dont know how to inject controller plugin to the service.
        $this->getServiceLocator()->get('payum.security.token_factory')->setUrlPlugin($this->url());

        $captureToken = $this->getServiceLocator()->get('payum.security.token_factory')->createCaptureToken(
            'paypal_ec', $details, 'payment_done'
        );

        $this->redirect()->toUrl($captureToken->getTargetUrl());
    }
}
```

## Done action

We will be redirected to it after the payment is done. Let's start from action:

```php
<?php
namespace Application\Controller;

use Payum\Core\Request\GetHumanStatus;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocator;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
    public function doneAction()
    {
        $token = $this->getServiceLocator()->get('payum.security.http_request_verifier')->verify($this->getRequest());

        $payment = $this->getServiceLocator()->get('payum')->getPayment($token->getPaymentName());

        $payment->execute($status = new GetHumanStatus($token));

        return new JsonModel(array('status' => $status->getStatus()) + iterator_to_array($status->getModel()));
    }
}
```

_**Note**: You would have to enable json strategy in view_manager to make it work. More details in this [article](http://akrabat.com/zend-framework-2/returning-json-from-a-zf2-controller-action/)_

and route for it:

```php
<?php
// module/Application/config/module.config.php

return array(
    'router' => array(
        'routes' => array(
            'payment_done' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/payment/done[/:payum_token]',
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
