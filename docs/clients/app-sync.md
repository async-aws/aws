---
layout: client
category: clients
name: AppSync
package: async-aws/app-sync
---

## Usage

### Create resolver

```php
use AsyncAws\AppSync\AppSyncClient;
use AsyncAws\AppSync\Enum\ResolverKind;

$appSync = new AppSyncClient();

$requestMapping = file_get_contents('/mappings/echo.req.vtl');
$responseMapping  = file_get_contents('/mappings/echo.res.vtl');

$appSync->createResolver([
    'apiId' => 'my-api',
    'typeName' => 'Query',
    'fieldName' => 'echo',
    'dataSourceName' => 'EchoApi',
    'requestMappingTemplate' => $requestMapping,
    'responseMappingTemplate' => $responseMapping,
    'kind' => ResolverKind::UNIT,
]);
```

### List resolvers

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$appSync->listResolvers([
    'apiId' => 'my-api',
    'typeName' => 'Query',
    'maxResults' => 100,
]);
```

### Start schema creation

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$schemaDefinition = file_get_contents('/schema.graphql');

$appSync->startSchemaCreation([
    'apiId' => 'my-api',
    'definition' => $schemaDefinition,
]);
```

### List API keys

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$appSync->listApiKeys([
    'apiId' => 'my-api',
    'maxResults' => 10,
]);
```
