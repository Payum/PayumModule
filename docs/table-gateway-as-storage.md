# Table gateway as storage

In this chapter we would show how to use Zend TableGateway to store payment details

## Configuration

A Storage class for use with Payum's StorageExtension, which uses ZendFramework's TableGateway database abstraction
classes. The bulk of the work is handled by the TableGateway passed in via the constructor. This can be configured
with hydrators and an object prototype (in a HydratingResultSet) to control what model is returned / handled and how
it is persisted in the database.

E.g. Your service factory could be as follows (values in <chevrons> may need to be altered for your implementation
and you may wish to replace fully qualified classnames with aliases and use statements for brevity and readability):

```php

   array(
       'storageDetail' => function ($sm) {
           return new \Payum\Bridge\ZendTableGateway\Storage\ZendTableGateway(
               $sm->get('storageDetailTableGateway'),  // Your configured table gateway (defined below).
               '<Payment\Model\Transaction>'           // The classname of the entity that represents your data.
           );
       },
       'storageDetailTableGateway' => function ($sm) {
           return new \Zend\Db\TableGateway\TableGateway(
               '<transaction>',                        // Your database table name.
               $sm->get('<Zend\Db\Adapter\Adapter>'),  // Your configured database adapter.
               null,
               new HydratingResultSet(
                   $sm->get('<transactionHydrator>'),  // Hydrator to hydrate your data entity (defined below).
                   $sm->get('<transactionEntity>')     // The entity that represents your data (defined below).
               )
           );
       },
       'transactionHydrator' => function ($sm) {
           return new <\Zend\Stdlib\Hydrator\ClassMethods()>;
       },
       'transactionEntity' => function ($sm) {
           return new <\Payment\Model\Transaction()>;  // The entity that represents your data (usually matching the
                                                       // string passed in parameter two of ZendTableGateway above).
       },
   )
```

Back to [index](index.md).
