---
layout: client
category: clients
name: Iam
package: async-aws/iam
---

## Usage

### Invoke a function

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
