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
    'apiId' => 'abcd1234',
    'typeName' => 'Query',
    'fieldName' => 'echo',
    'dataSourceName' => 'EchoApi',
    'requestMappingTemplate' => $requestMapping,
    'responseMappingTemplate' => $responseMapping,
    'kind' => ResolverKind::UNIT,
]);
```

### Delete resolver

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$appSync->deleteResolver([
    'apiId' => 'abcd1234',
    'typeName' => 'Query',
    'fieldName' => 'echo',
]);
```

### Get schema creation status

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$appSync->getSchemaCreationStatus([
    'apiId' => 'abcd1234',
]);
```

### List API keys

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$appSync->listApiKeys([
    'apiId' => 'abcd1234',
    'maxResults' => 10,
]);
```

### List resolvers

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$appSync->listResolvers([
    'apiId' => 'abcd1234',
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
    'apiId' => 'abcd1234',
    'definition' => $schemaDefinition,
]);
```

### Update API key

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$expiration = strtotime('+1 year');

$appSync->updateApiKey([
    'apiId' => 'abcd1234',
    'id' => 'da2-i43lhlsasbif73gfjqw653',
    'description' => 'Key used by application X',
    'expires' => $expiration,
]);
```

### Update data source

```php
use AsyncAws\AppSync\AppSyncClient;

$appSync= new AppSyncClient();

$appSync->updateDataSource([
    'apiId' => 'abcd1234',
    'name' => 'EchoApi',
    'description' => 'Echo API resolver',
    'type' => 'Query',
    'httpConfig' => new HttpDataSourceConfig([
        'endpoint' => 'https://echo.foo',
    ]),
]);
```

### Update resolver

```php
use AsyncAws\AppSync\AppSyncClient;
use AsyncAws\AppSync\Enum\ResolverKind;

$appSync= new AppSyncClient();

$requestMapping = file_get_contents('/mappings/echo.req.vtl');
$responseMapping  = file_get_contents('/mappings/echo.res.vtl');

$appSync->updateResolver([
    'apiId' => 'abcd1234',
    'typeName' => 'Query',
    'fieldName' => 'echo',
    'dataSourceName' => 'EchoApi',
    'requestMappingTemplate' => $requestMapping,
    'responseMappingTemplate' => $responseMapping,
    'kind' => ResolverKind::UNIT,
]);
```
