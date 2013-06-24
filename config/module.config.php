<?php
return array(
    'payum' => array(
    ),
    
    'service_manager' => array(
        'factories' => array(
            'payum' => 'Payum\PayumModule\ServiceFactory\RegistryFactory',
            'payum.token_manager' => 'Payum\PayumModule\ServiceFactory\TokenManagerFactory',
        ),
    ),
);