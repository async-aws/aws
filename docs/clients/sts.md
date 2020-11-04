---
layout: client
category: clients
name: STS
package: async-aws/core
fqcn: AsyncAws\Core\Sts\StsClient
---

## Usage

### Retrieve current Account

```php
use AsyncAws\Core\Sts\StsClient;

$sts = new StsClient();

$result = $sts->getCallerIdentity();

echo 'current Account:' . $result->getAccount();
```

### Retrieve current Account

```php
use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\StsClient;

$sts = new StsClient();

$result = $sts->assumeRole(new AssumeRoleRequest([
    'RoleArn' => 'arn:aws::iam::123456789012:role/demo',
    'RoleSessionName' => 'demo-session',
    'DurationSeconds' => 3600,
]));

echo 'AccessKeyId:' . $result->getCredentials()->getAccessKeyId().PHP_EOL;
echo 'SecretAccessKey:' . $result->getCredentials()->getSecretAccessKey().PHP_EOL;
echo 'SessionToken:' . $result->getCredentials()->getSessionToken();
```
