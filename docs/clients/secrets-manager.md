---
layout: client
category: clients
name: SecretManager
package: async-aws/secrets-manager
---

## Usage

### Create secret

```php
use AsyncAws\SecretsManager\Input\CreateSecretRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretManager = new SecretsManagerClient();
$input = new CreateSecretRequest([
    'Name' => 'keyName',
    'Description' => 'Test description',
    'SecretString' => 'secretValue',
]);
$result = $secretManager->CreateSecret($input);
$result->resolve();
```

### Update secret

```php
use AsyncAws\SecretsManager\Input\PutSecretValueRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretManager = new SecretsManagerClient();
$input = new PutSecretValueRequest([
    'SecretId' => 'keyName',
    'SecretString' => 'testPutSecret',
]);
$result = $secretManager->PutSecretValue($input);
$result->resolve();
```

### Update secret value

```php
use AsyncAws\SecretsManager\Input\UpdateSecretRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretManager = new SecretsManagerClient();
$input = new UpdateSecretRequest([
    'SecretId' => 'keyName',
    'Description' => 'New description',
]);
$result = $secretManager->updateSecret($input);
$result->resolve();
```

### Delete secret
```php
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretManager = new SecretsManagerClient();
$result = $secretManager->deleteSecret([
    'SecretId' => 'keyName',
]);
$result->resolve();
```

### Get secret

```php
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretManager = new SecretsManagerClient();
$entry = $secretManager->getSecretValue([
    'SecretId' => 'keyName',
]);
echo $entry->getSecretString();
```


### List secrets

```php
use AsyncAws\SecretsManager\SecretsManagerClient;
use AsyncAws\SecretsManager\ValueObject\SecretListEntry;

$secretManager = new SecretsManagerClient();
$secrets = $secretManager->listSecrets();
/** @var SecretListEntry $entry */
foreach ($secrets as $entry) {
    echo $entry->getName();
}
```
