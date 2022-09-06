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

### Create group

```php
use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;

$cognitoIdp = new CognitoIdentityProviderClient();

$result = $this->cognitoIdp->createGroup([
    'UserPoolId' => 'us-east-1_1337oL33t',
    'GroupName' => '1c202820-8eb5-11ea-bc55-0242ac130003',
]);

/** @var GroupType $group */
$group = $result->getGroup();
echo 'Group name is: ' . $group->getGroupName() . PHP_EOL;
```

### List groups

```php
use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;

$cognitoIdp = new CognitoIdentityProviderClient();

$result = $this->cognitoIdp->listGroups([
    'UserPoolId' => 'us-east-1_1337oL33t',
]);

/** @var iterable $groups */
$groups = $result->getGroups();
/** @var GroupType $group */
foreach ($groups as $group) {
    echo 'Group name is: ' . $group->getGroupName() . PHP_EOL;
}
```

### Add user to group as an admin

```php
use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;

$cognitoIdp = new CognitoIdentityProviderClient();

//If the action is successful, the response has an empty body.
$this->cognitoIdp->adminAddUserToGroup([
    'UserPoolId' => 'us-east-1_1337oL33t',
    'Username' => 'username',
    'GroupName' => 'group_name',
]);
```

### Remove user from group as an admin

```php
use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
$cognitoIdp = new CognitoIdentityProviderClient();

//If the action is successful, the response has an empty body.
$this->cognitoIdp->adminRemoveUserFromGroup([
    'UserPoolId' => 'us-east-1_1337oL33t',
    'Username' => 'username',
    'GroupName' => 'group_name',
]);
```

### Revoke token

```php
use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
$cognitoIdp = new CognitoIdentityProviderClient();

//If the action is successful, the response has an empty body.
$this->cognitoIdp->revokeToken([
    'Token' => 'refresh_token',
    'ClientId' => 'client_id',
    'ClientSecret' => 'client_secret',
]);
```
