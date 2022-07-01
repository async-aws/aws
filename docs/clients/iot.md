---
layout: client
category: clients
name: Iot
package: async-aws/iot
---

## Usage

### List Things

```php
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Input\ListThingsRequest;

$iam = new IotClient();

$things = $iam->listThings(new ListThingsRequest([
    'thingTypeName' => 'hvac',
]));

foreach ($things as $thing) {
    echo $thing->getThingName().' '.$thing->getThingTypeName().PHP_EOL;
}
```
