---
category: integration
---

# Monolog integration

The Monolog integration supports both Monolog version 1 and version 2.

## Install

```shell
composer require async-aws/monolog-cloud-watch
```

## Usage

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
$logger->error('an error occurred');
```

## Configuration

The CloudWatchLogsHandler accepts the following parameters:

- `$client`: the CloudWatchLogsClient instance
- `$group`: name of the CloudWatch Log Group which was created previously
- `$stream`: name of the CloudWatch Log Stream which was created previously
- `$batchSize`: number of log records that are pushed to CloudWatch in a single call. This number cannot exceed 10,000.
- `$level`: minimum logging level at which this handler will be triggered (see https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md#log-levels)
- `$bubble`: whether the messages that are handled can bubble up the stack or not (see https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md#core-concepts)

## Symfony usage

To ease service configuration, install the [AsyncAws Symfony Bundle](./symfony-bundle.md) first.

The bundle will automatically configure CloudWatchLogsClient with the given credentials.

```yaml
# config/services.yaml

services:
    monolog.handler.cloud_watch:
        class: AsyncAws\Monolog\CloudWatch\CloudWatchLogsHandler
        arguments:
            - '@async_aws.client.cloud_watch_logs'
            - company-website
            - frontend-api
            # more arguments, see Configuration
```

```yaml
# config/packages/prod/monolog.yaml

monolog:
    handlers:
        main:
            type: fingers_crossed
            action_level: error
            handler: cloud_watch
        cloud_watch:
            type: service
            id: monolog.handler.cloud_watch
```

## Limitations

The CloudWatchLogs Monolog relies on the AWS CloudWatch Logs API and inherits the underlying limitations, which are
described in the [official documentation](https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html).

The integration enforces a few limitations like the maximum batch size, maximum number of events per batch and
chronological order of events; which should not be taken care of by the developer.

Please note that CloudWatch Logs accepts only 5 requests per second (with 10,000 log items each) per log stream. Each
PHP process will cause a new request, regardless of whether the 10,000 items limit was reached. If an application is
expected to push log records at high rate, you should use different log streams.

The AWS credentials should have at least the `logs:DescribeLogStreams` and `logs:PutLogEvents` permissions for the
integration to work properly.
