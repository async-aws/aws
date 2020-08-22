---
layout: client
category: clients
name: CodeDeploy
package: async-aws/code-deploy
---

## Usage

### Sets the result of a Lambda validation function

```php
use AsyncAws\CodeDeploy\CodeDeployClient;
use AsyncAws\CodeDeploy\Enum\LifecycleEventStatus;
use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;

$codeDeploy = new CodeDeployClient();

$event = []; // fetched from lambda

$status = $codeDeploy->putLifecycleEventHookExecutionStatus(new PutLifecycleEventHookExecutionStatusInput([
    'deploymentId' => $event['deploymentId'],
    'lifecycleEventHookExecutionId' => $event['lifecycleEventHookExecutionId'],
    'status' => LifecycleEventStatus::SUCCEEDED
]));

echo 'ExecutionId: '. $status->getLifecycleEventHookExecutionId();
```
