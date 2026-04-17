---
layout: client
category: clients
name: EC2
package: async-aws/ec2
---

## Usage

### Describe images

```php
use AsyncAws\Ec2\Ec2Client;
use AsyncAws\Ec2\ValueObject\Filter;

$ec2 = new Ec2Client();

$result = $ec2->describeImages([
    'Owners' => ['self'],
    'Filters' => [
        new Filter(['Name' => 'state', 'Values' => ['available']]),
    ],
]);

foreach ($result as $image) {
    echo $image->getImageId() . ' ' . $image->getCreationDate() . PHP_EOL;
    foreach ($image->getBlockDeviceMappings() as $bdm) {
        if (null !== $ebs = $bdm->getEbs()) {
            echo '  snapshot: ' . $ebs->getSnapshotId() . PHP_EOL;
        }
    }
}
```

### Deregister an image

```php
use AsyncAws\Ec2\Ec2Client;

$ec2 = new Ec2Client();

$ec2->deregisterImage([
    'ImageId' => 'ami-0abcdef1234567890',
]);
```

### Delete a snapshot

```php
use AsyncAws\Ec2\Ec2Client;

$ec2 = new Ec2Client();

$ec2->deleteSnapshot([
    'SnapshotId' => 'snap-0abcdef1234567890',
]);
```
