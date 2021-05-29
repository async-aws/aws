---
layout: client
category: clients
name: Kinesis
package: async-aws/kinesis
---

## Usage

### List Streams

```php
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\Input\ListUsersRequest;

$kinesis = new KinesisClient();

$streams = $kinesis->listStreams();

foreach ($streams as $stream) {
    echo $stream;
}
```
