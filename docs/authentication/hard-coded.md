---
category: authentication
---

# Using Hard-Coded Configuration

When developing, and debugging, the simplest way to configure the client, is to set the credentials in the
client configuration parameters.

## Authenticate with access key and secret

```php
use AsyncAws\Core\AwsClientFactory;

$client = new AwsClientFactory([
    'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
    'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
]);
```

## Assuming a role

You can tell the API client to use specific role by providing a ARN to 
the `roleArn` key. You can also specify a `roleSessionName`.

```php
use AsyncAws\Core\AwsClientFactory;

$client = new AwsClientFactory([
    'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
    'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
    'roleArn' => 'arn:aws:iam::1234567891011:role/name-of-my-role',
    'roleSessionName' => 'my-session-name', // Optionnal
]);

> **Warning**: Hard-coding your credentials can be dangerous because it’s easy to commit your credentials into an SCM
> repository accidentally
