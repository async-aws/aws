---
layout: client
category: clients
name: CognitoIdentityProvider
package: async-aws/cognito-identity-provider
---

## Usage

### Create a user as an admin

```php
use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;

$cognitoIdp = new CognitoIdentityProviderClient();

$cognitoIdp->adminCreateUser([
    'UserPoolId' => 'us-east-1_1337oL33t',
    'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
    'UserAttributes' => [
        new AttributeType(['Name' => 'phone_number', 'Value' => '+33600000000'])
    ],
]);
```

### Get a user as an admin

```php
use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;

$cognitoIdp = new CognitoIdentityProviderClient();

$result = $cognitoIdp->adminGetUser([
    'UserPoolId' => 'us-east-1_1337oL33t',
    'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
    'UserAttributes' => [
        new AttributeType(['Name' => 'phone_number', 'Value' => '+33600000000'])
    ],
]);

echo 'User status is: ' . $result->getUserStatus() . PHP_EOL;
$givenNameAttribute = current(array_filter(
    $result->getUserAttributes(),
    function ($attr) { return $attr->getName() === 'given_name'; }
));
echo 'User given name is: ' . $givenNameAttribute->getValue() . PHP_EOL;
```
