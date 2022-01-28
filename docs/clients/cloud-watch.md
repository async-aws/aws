---
layout: client
category: clients
name: CloudWatch
package: async-aws/cloud-watch
---

## Usage

### Get metric data

```php
use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use DateTimeImmutable;

$cloudWatch = new CloudWatchClient();

$result = $cloudWatch->getMetricData(new GetMetricDataInput([
    'MetricDataQueries' => [new MetricDataQuery(['Id' => 'metric-id'])],
    'StartTime' => new DateTimeImmutable('2021-07-01T00:00:00'),
    'EndTime' => new DateTimeImmutable('2021-07-01T12:00:00'),
]));

foreach ($result->getMetricDataResults() as $metricData) {
    var_dump($metricData);
}
```
