---
layout: client
category: clients
name: Route53
package: async-aws/route53
---

## Usage

### Create hosted zone

```php
use AsyncAws\Route53\Route53Client;

$route53 = new Route53Client();

foreach ($route53->listHostedZones() as $zone) {
    echo $zone->getName();
}
```
