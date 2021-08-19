---
layout: client
category: clients
name: SecretsManager
package: async-aws/secrets-manager
---

## Usage

### Create secret

```php
use AsyncAws\SecretsManager\Input\CreateSecretRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretsManager = new SecretsManagerClient();
$input = new CreateSecretRequest([
    'Name' => 'keyName',
    'Description' => 'Test description',
    'SecretString' => 'secretValue',
]);
$result = $secretsManager->CreateSecret($input);
$result->resolve();
```

### Update secret

```php
use AsyncAws\SecretsManager\Input\PutSecretValueRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretsManager = new SecretsManagerClient();
$input = new PutSecretValueRequest([
    'SecretId' => 'keyName',
    'SecretString' => 'testPutSecret',
]);
$result = $secretsManager->PutSecretValue($input);
$result->resolve();
```

### Update secret value

```php
use AsyncAws\SecretsManager\Input\UpdateSecretRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretsManager = new SecretsManagerClient();
$input = new UpdateSecretRequest([
    'SecretId' => 'keyName',
    'Description' => 'New description',
]);
$result = $secretsManager->updateSecret($input);
$result->resolve();
```

### Delete secret
```php
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretsManager = new SecretsManagerClient();
$result = $secretsManager->deleteSecret([
    'SecretId' => 'keyName',
]);
$result->resolve();
```

### Get secret

```php
use AsyncAws\SecretsManager\SecretsManagerClient;

$secretsManager = new SecretsManagerClient();
$entry = $secretsManager->getSecretValue([
    'SecretId' => 'keyName',
]);
echo $entry->getSecretString();
```


### List secrets

```php
use AsyncAws\SecretsManager\SecretsManagerClient;
use AsyncAws\SecretsManager\ValueObject\SecretListEntry;

$secretsManager = new SecretsManagerClient();
$secrets = $secretsManager->listSecrets();
/** @var SecretListEntry $entry */
foreach ($secrets as $entry) {
    echo $entry->getName();
}
```
