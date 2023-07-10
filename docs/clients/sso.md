---
layout: client
category: clients
name: SSO
package: async-aws/sso
fqcn: AsyncAws\Sso\SsoClient
---

## Usage

### Retrieve role credentials

```php
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;
use AsyncAws\Sso\SsoClient;

$client = new SsoClient();

$result = $client->getRoleCredentials(new GetRoleCredentialsRequest([
    'roleName' => 'YourRoleName',
    'accountId' => 'YourAccountId',
    'accessToken' => 'YourAccessToken',
]));

echo 'AccessKeyId:' . $result->getRoleCredentials()->getAccessKeyId().PHP_EOL;
echo 'Expiration:' . $result->getRoleCredentials()->getExpiration().PHP_EOL;
echo 'SecretAccessKey:' . $result->getRoleCredentials()->getSecretAccessKey().PHP_EOL;
echo 'SessionToken:' . $result->getRoleCredentials()->getSessionToken();
```
