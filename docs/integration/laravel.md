---
category: integration
---

# AsyncAws for Laravel

The AsyncAws integrations for Laravel are similar to the integrations for the
official AWS SDK. The sections below describe how you can install and configure
Cache and Queue.

## Illuminate Cache

Use DynamoDb as a Illuminate Cache store.

### Install

```shell
composer require async-aws/illuminate-cache
```

Go to Aws Console to create a DynamoDb table. It is important that the table is using
a Primary key (also called Partition key) to `key`. Any other value of the Primary
key will not work.

### Configure

```diff
# config/cache.php

 'dynamodb' => [
-    'driver' => 'dynamodb',
+    'driver' => 'async-aws-dynamodb',
     'key' => env('AWS_ACCESS_KEY_ID'),
     'secret' => env('AWS_SECRET_ACCESS_KEY'),
     'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
     'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
     'endpoint' => env('DYNAMODB_ENDPOINT'),
 ],
```

## Illuminate Queue

Use SQS with Illuminate Queue.

### Install

```shell
composer require async-aws/illuminate-queue
```

### Configure

```diff
# config/queue.php

 'sqs' => [
-    'driver' => 'sqs',
+    'driver' => 'async-aws-sqs',
     'key' => env('AWS_ACCESS_KEY_ID'),
     'secret' => env('AWS_SECRET_ACCESS_KEY'),
     'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
     'queue' => env('SQS_QUEUE', 'your-queue-name'),
     'suffix' => env('SQS_SUFFIX'),
     'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
 ],
```
