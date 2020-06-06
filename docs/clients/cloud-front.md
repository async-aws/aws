---
layout: client
category: clients
name: CloudFront
package: async-aws/cloud-front
---

## Usage

### Invalidate paths

```php
use AsyncAws\CloudFront\CloudFrontClient;
use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\CloudFront\ValueObject\Paths;

$cloudFront = new CloudFrontClient();

$paths = ['/assets/images/cat.jpg', '/image/*'];
$cloudFront->createInvalidation([
    'DistributionId' => 'EQ44GEF5FAALL',
    'InvalidationBatch' => new InvalidationBatch([
        'Paths' => new Paths([
            'Quantity' => count($paths),
            'Items' => $paths,
        ]),
        'CallerReference' => time(),
    ]),
]);
```
