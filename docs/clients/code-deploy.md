---
layout: client
category: clients
name: CodeDeploy
package: async-aws/code-deploy
---

## Usage

### Create deployment

```php
use AsyncAws\CodeDeploy\CodeDeployClient;
use AsyncAws\CodeDeploy\Enum\RevisionLocationType;
use AsyncAws\CodeDeploy\Input\CreateDeploymentInput;

$codeDeploy = new CodeDeployClient();

$deployment = $codeDeploy->createDeployment(mew CreateDeploymentInput([
    'applicationName' => 'my-app',
    'deploymentGroupName' => 'my-deployment-group',
    'revision' => [
        'revisionType' => RevisionLocationType::GIT_HUB,
        'gitHubLocation' => [
            'repository' => 'repo',
            'commitId' => 'commit',
        ]
    ],
]));

echo 'DepoymentId: '. $deployment->getDeploymentId();

```

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
