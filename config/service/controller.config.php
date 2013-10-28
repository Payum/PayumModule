<?php

use Payum\PayumModule\Controller\CaptureController;

return array(
    'factories' => array(
        'PayumCapture' => function ($cm) {
            $sm = $cm->getServiceLocator();

            // Construct Capture controller with required Payum Registry and HttpRequestVerifier dependencies.
            return new CaptureController($sm->get('payum'), $sm->get('payum.security.http_request_verifier'));
        },
    ),
);