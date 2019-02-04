<?php

use Payum\PayumModule\Controller\AuthorizeController;
use Payum\PayumModule\Controller\CaptureController;
use Payum\PayumModule\Controller\NotifyController;
use Payum\PayumModule\Controller\RefundController;

return array(
    'factories' => array(
        'PayumCapture' => function ($cm) {
            $sm = $cm->getServiceLocator();

            // Construct Capture controller with required Payum Registry and HttpRequestVerifier dependencies.
            return new CaptureController($sm->get('payum'), $sm->get('payum.security.http_request_verifier'));
        },
        'PayumAuthorize' => function ($cm) {
            $sm = $cm->getServiceLocator();

            // Construct Authorize controller with required Payum Registry and HttpRequestVerifier dependencies.
            return new AuthorizeController($sm->get('payum'), $sm->get('payum.security.http_request_verifier'));
        },
        'PayumNotify' => function ($cm) {
            $sm = $cm->getServiceLocator();

            // Construct Capture controller with required Payum Registry and HttpRequestVerifier dependencies.
            return new NotifyController($sm->get('payum'), $sm->get('payum.security.http_request_verifier'));
        },
        'PayumRefund' => function ($cm) {
            $sm = $cm->getServiceLocator();

            // Construct Capture controller with required Payum Registry and HttpRequestVerifier dependencies.
            return new RefundController($sm->get('payum'), $sm->get('payum.security.http_request_verifier'));
        },
    ),
);