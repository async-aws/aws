---
category: integration
---

# DynamoDB Sessions integration

Store your PHP Sessions in DynamoDB.

## Install

```shell
composer require async-aws/dynamo-db-session
```

## Usage

```php
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDbSession\SessionHandler;

\session_set_save_handler(new SessionHandler(new DynamoDbClient(), [
  'table_name' => 'php-sessions',
]), true);
```

A DynamoDb table needs to exist in the configured region with the given `table_name` config.

The primary key of the table must be a String with key "id". This can be changed with the `hash_key` config.

The Time to live attribute of the table must be set on the "expires" attribute. This can be changed with the `session_lifetime_attribute` config.

## Configuration

The SessionHandler accepts the following configuration parameters:

| Parameter                  | Description                                      | Default                           |
|----------------------------|--------------------------------------------------|-----------------------------------|
| consistent_read            | Whether or not to use consistent reads           | true                              |
| data_attribute             | Name of the data attribute in table              | "data"                            |
| hash_key                   | Name of hash key in table                        | "id"                              |
| session_lifetime           | Lifetime of inactive sessions expiration         | ini_get('session.gc_maxlifetime') |
| session_lifetime_attribute | Name of the session life time attribute in table | "expires"                         |
| table_name                 | Name of table to store the sessions              |                                   |

## Symfony usage

To ease service configuration, install the [AsyncAws Symfony Bundle](./symfony-bundle.md) first.

The bundle will automatically configure DynamoDbClient with the given credentials.

```yaml
# config/services.yaml

services:
    AsyncAws\DynamoDbSession\SessionHandler:
        arguments:
            - '@async_aws.client.dynamo_db'
            - table_name: php-sessions
              # for more parameters, see Configuration
```

```yaml
# config/packages/framework.yaml

framework:
    session:
        handler_id: AsyncAws\DynamoDbSession\SessionHandler
```
