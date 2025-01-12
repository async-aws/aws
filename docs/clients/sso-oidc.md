---
layout: client
category: clients
name: SSOOIDC
package: async-aws/sso-oidc
fqcn: AsyncAws\Sso\SsoOidcClient
---

## Usage

### Create a token

```php
use AsyncAws\SsoOidc\Input\CreateToken;
use AsyncAws\SsoOidc\SsoOidcClient;

$client = new SsoOidcClient();

$result = $client->createToken(new CreateToken([
    'clientId' => 'YourClientId',
    'clientSecret' => 'YourClientSecret',
    'grantType' => 'authorization_code',
]));

echo 'AccessToken:' . $result->getAccessToken().PHP_EOL;
echo 'Expiration:' . $result->getExpiresIn().PHP_EOL;
```
