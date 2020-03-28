---
category: authentication
---

# Using Hard-Coded Configuration

When developing, and debugging, the simplest way to configure the client, is to set the credentials in the
client configuration parameters.

```php
use AsyncAws\Core\AwsClientFactory;

$client = new AwsClientFactory([
    'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
    'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
]);
```

> **Warning**: Hard-coding your credentials can be dangerous because it’s easy to commit your credentials into an SCM
> repository accidentally
