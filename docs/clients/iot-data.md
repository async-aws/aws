---
layout: client
category: clients
name: IotData
package: async-aws/iot-data
---

## Usage

### List Things

```php
use AsyncAws\IotData\IotDataClient;
use AsyncAws\IotData\Input\GetThingShadowRequest;

$client = new IotDataClient();

$response = $client->getThingShadow(new GetThingShadowRequest([
    'thingName' => 'unit1:hvac',
]));

echo $response->getPayload();
```
