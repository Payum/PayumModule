<?php
return array(
    'factories' => array(
        'payum' => 'Payum\PayumModule\Registry\RegistryFactory',
        'payum.options' => 'Payum\PayumModule\Options\PayumOptionsFactory',
        'payum.security.token_storage' => 'Payum\PayumModule\Security\TokenStorageFactory',
        'payum.security.token_factory' => 'Payum\PayumModule\Security\TokenFactoryFactory',
        'payum.security.http_request_verifier' => 'Payum\PayumModule\Security\HttpRequestVerifierFactory',
    ),
);