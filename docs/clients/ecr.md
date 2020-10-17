---
layout: client
category: clients
name: ECR
package: async-aws/ecr
---

## Usage

### Invoke a function

```php
use AsyncAws\Ecr\EcrClient;
use AsyncAws\Ecr\Input\GetAuthorizationTokenRequest;

$ecr = new EcrClient();

$authorizationToken = $ecr->getAuthorizationToken(new GetAuthorizationTokenRequest([
    'registryIds' => '000000000000',
]));

foreach ($authorizationToken->getAuthorizationData() as $authorizationData) {
    echo 'Authorization Token: '.$authorizationData->getAuthorizationToken().PHP_EOL;
    echo 'Expires At: '.$authorizationData->getExpiresAt()->format('Y-m-d H:i:s').PHP_EOL;
    echo 'Proxy Endpoint: '.$authorizationData->getProxyEndpoint().PHP_EOL;
}
```
