---
layout: client
category: clients
name: CloudWatchLogs
package: async-aws/cloud-watch-logs
---

## Usage

### List log streams

```php
use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;

$cloudWatchLogs = new CloudWatchLogsClient();

$result = $cloudWatchLogs->describeLogStreams(new DescribeLogStreamsRequest([
    'logGroupName' => 'company-website',
]));

foreach ($result->getLogStreams() as $stream) {
    echo '-'.$stream->getLogStreamName().PHP_EOL;
}
```

### Push logs

```php
use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;

$cloudWatchLogs = new CloudWatchLogsClient();

// sequenceToken is not required for a new stream
$currentSequenceToken = null;

$result = $cloudWatchLogs->putLogEvents(new PutLogEventsRequest([
    'logGroupName' => 'company-website',
    'logStreamName' => 'frontend-api',
    'logEvents' => [
        new InputLogEvent([
            'timestamp' => date('U.u') * 1000,
            'message' => 'an error occurred',
        ]),
    ],
    'sequenceToken' => $currentSequenceToken,
]));

$currentSequenceToken = $result->getNextSequenceToken();
```
