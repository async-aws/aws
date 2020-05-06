---
category: integration
---

# Monolog integration

The Monolog integration supports both Monolog version 1 and version 2.

## Install

```shell
composer require async-aws/monolog-cloudwatch
```

## Use

```php
use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\Monolog\CloudWatch\CloudWatchLogsHandler;
use Monolog\Logger;

$client = new CloudWatchLogsClient();
$groupName = 'company-website';
$streamName = 'frontend-api';

$handler = new CloudWatchLogsHandler($client, $groupName, $streamName);

$logger = new Logger('logger');
$logger->pushHandler($handler);
$logger->error('an error occured');
```
