<?php
return array(
    'payum' => array(
        'token_storage' => null,
        'payments' => array(),
        'storages' => array(),
    ),
    'controllers' => array(
        'invokables' => array(
            'PayumCapture' => 'Payum\PayumModule\Controller\CaptureController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'payum_capture_do' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/payment/capture[/:payum_token]',
                    'constraints' => array(
                        'payum_token' => '[a-zA-Z0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'PayumCapture',
                        'action' => 'do'
                    ),
                ),
            ),
        ),
    ),
);