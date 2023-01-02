---
layout: client
category: clients
name: Iam
package: async-aws/iam
---

## Usage

### List Users

```php
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\ListUsersRequest;

$iam = new IamClient();

$users = $iam->listUsers(new ListUsersRequest([
    'PathPrefix' => '/division_engineering/subdivision_web',
]));

foreach ($users as $user) {
    echo $user->getUserName().' '.($user->getPasswordLastUsed() ? $user->getPasswordLastUsed()->format('Y-m-d') : '').PHP_EOL;
}
```

### Create / Delete a user's individual policy document

```php
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\PutUserPolicyRequest;
use AsyncAws\Iam\Input\DeleteUserPolicyRequest;

$iam = new IamClient();

$iam->putUserPolicy(new PutUserPolicyRequest([
    'UserName' => 'Thomas',
    'PolicyName' => 'Disallow Access To Everything',
    'PolicyDocument' => '{"Version":"2012-10-17","Statement":{"Effect":"Deny","Action":"*","Resource":"*"}}',
]));

// Uh-oh, that policy is a bit *too* restrictive, let's delete it

$iam->deleteUserPolicy(new DeleteUserPolicyRequest([
    'UserName' => 'Thomas',
    'PolicyName' => 'Disallow Access To Everything',
]));
```

### Create service-specific credentials

```php
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\CreateServiceSpecificCredentialRequest;

$iam = new IamClient();

$creds = $iam->createServiceSpecificCredential(new CreateServiceSpecificCredentialRequest([
    'UserName' => 'Thomas',
    'ServiceName' => 'codecommit.amazonaws.com',
]));

echo $creds->getServiceSpecificCredential()->getServiceUserName(); // example: thomas-at-123456789012
echo $creds->getServiceSpecificCredential()->getServicePassword(); // example: xTBAr/czp+D3EXAMPLE47lrJ6/43r2zqGwR3EXAMPLE=

```

### List service-specific credentials

```php
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\ListServiceSpecificCredentialsRequest;

$iam = new IamClient();

// list *all* service-specific credentials for this user
$result = $iam->listServiceSpecificCredentials(new ListServiceSpecificCredentialsRequest([
    'UserName' => 'Thomas',
]));

echo $result->getServiceSpecificCredentials()[0]->getServiceUserName(); // example: thomas-at-123456789012
echo $result->getServiceSpecificCredentials()[0]->getServiceSpecificCredentialId(); // example: ACCA67890FGHIEXAMPLE
echo $result->getServiceSpecificCredentials()[0]->getServiceName(); // example: codecommit.amazonaws.com
echo $result->getServiceSpecificCredentials()[1]->getServiceUserName(); // example: thomas-at-123456789012
echo $result->getServiceSpecificCredentials()[1]->getServiceSpecificCredentialId(); // example: IHGF09876ACCAEXAMPLE
echo $result->getServiceSpecificCredentials()[1]->getServiceName(); // example: dynamodb.amazonaws.com

// filter by AWS service
$result = $iam->listServiceSpecificCredentials(new ListServiceSpecificCredentialsRequest([
    'UserName' => 'Thomas',
    'ServiceName' => 'dynamodb.amazonaws.com',

echo $result->getServiceSpecificCredentials()[0]->getServiceUserName(); // example: thomas-at-123456789012
echo $result->getServiceSpecificCredentials()[0]->getServiceSpecificCredentialId(); // example: IHGF09876ACCAEXAMPLE
echo $result->getServiceSpecificCredentials()[0]->getServiceName(); // example: dynamodb.amazonaws.com
]));
```

### Delete service-specific credentials

```php
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\DeleteServiceSpecificCredentialRequest;

$iam = new IamClient();

$iam->deleteServiceSpecificCredential(new DeleteServiceSpecificCredentialRequest([
     // UserName is not required if the user owning the credentials is the same user as is authenticated via the SDK
     // put simply, this means that if you're deleting your own credentials you *do not* need to supply this parameter.
     // In all other cases you should probably include it.
     // see https://docs.aws.amazon.com/IAM/latest/APIReference/API_DeleteServiceSpecificCredential.html for more details
    'UserName' => 'Thomas',
    'ServiceSpecificCredentialId' => 'ACCA67890FGHIEXAMPLE'
]));
```
