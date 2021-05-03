---
layout: client
category: clients
name: Route53
package: async-aws/route53
---

## Usage

### List hosted zones

```php
use AsyncAws\Route53\Route53Client;

$route53 = new Route53Client();

foreach ($route53->listHostedZones() as $zone) {
    echo $zone->getName();
}
```


### Create hosted zone

```php
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\Route53Client;

$route53 = new Route53Client();

$route53->createHostedZone(
    new CreateHostedZoneRequest([
        'CallerReference' => 'uniqueId',
        'Name' => 'example.com',
        'HostedZoneConfig' => new HostedZoneConfig([
            'Comment' => 'foo',
        ]),
    ])
);
```
