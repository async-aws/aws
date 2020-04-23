---
layout: client
category: clients
name: SSM
package: async-aws/ssm
---

## Usage

### List parameters

```php
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\Parameter;

$ssm = new SsmClient();

$parameters = $ssm->getParametersByPath(new GetParametersByPathRequest([
    'Path' => '/projects/website',
    'Recursive' => true,
    'WithDecryption' => true,
]));

$secrets = [];
/** @var Parameter $parameter */
foreach ($parameters as $parameter) {
    $secrets[$parameter->getName()] = $parameter->getValue();
}
```

### Store an encrypted parameter

```php
use AsyncAws\Ssm\Enum\ParameterType;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\SsmClient;

$ssm = new SsmClient();
$parameters = $ssm->putParameter(new PutParameterRequest([
    'Name' => '/projects/website/database_password',
    'Value' => strtr(base64_encode(random_bytes(24)), '+/', '-_'),
    'Type' => ParameterType::SECURE_STRING,
    // 'KeyId' => $customKmsKeyId,
]));
```
