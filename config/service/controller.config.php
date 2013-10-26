<?php

use Payum\PayumModule\Controller\CaptureController;

return array(
    'factories' => array(
        'PayumCapture' => function ($cm) {
            $sm = $cm->getServiceLocator();

            $controller = new CaptureController();
            $controller->setPayum($sm->get('payum'));
            $controller->setHttpRequestVerifier($sm->get('payum.security.http_request_verifier'));
            return $controller;
        },
    ),
);