<?php
return array(
    'router' => array(
        'routes' => array(
            'payum_capture_do' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/payment/capture[/:payum_token]',
                    'constraints' => array(
                        'payum_token' => '[a-zA-Z0-9_-]+'
                    ),
                    'defaults' => array(
                        'controller' => 'PayumCapture',
                        'action' => 'do'
                    ),
                ),
            ),
            'payum_notify_do' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/payment/notify[/:payum_token]',
                    'constraints' => array(
                        'payum_token' => '[a-zA-Z0-9_-]+'
                    ),
                    'defaults' => array(
                        'controller' => 'PayumNotify',
                        'action' => 'do'
                    ),
                ),
            ),
            'payum_authorize_do' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/payment/authorize[/:payum_token]',
                    'constraints' => array(
                        'payum_token' => '[a-zA-Z0-9_-]+'
                    ),
                    'defaults' => array(
                        'controller' => 'PayumAuthorize',
                        'action' => 'do'
                    ),
                ),
            ),
            'payum_refund_do' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/payment/refund[/:payum_token]',
                    'constraints' => array(
                        'payum_token' => '[a-zA-Z0-9_-]+'
                    ),
                    'defaults' => array(
                        'controller' => 'PayumRefund',
                        'action' => 'do'
                    ),
                ),
            ),
        ),
    ),
);
