---
layout: client
category: clients
name: StepFunctions
package: async-aws/step-functions
---

## Usage

### Start a state machine execution

```php
use AsyncAws\StepFunctions\Input\StartExecutionInput;
use AsyncAws\StepFunctions\StepFunctionsClient;

$stepFunctions = new StepFunctionsClient();

$result = $stepFunctions->startExecution(new StartExecutionInput([
    'stateMachineArn' => 'arn:sfn',
    'input' => '{"foo": "bar"}',
]));

echo $result->getExecutionArn();
```

### Send a task heartbeat

```php
use AsyncAws\StepFunctions\Input\SendTaskHeartbeatInput;
use AsyncAws\StepFunctions\StepFunctionsClient;

$stepFunctions = new StepFunctionsClient();

$result = $stepFunctions->sendTaskHeartbeat(new SendTaskHeartbeatInput([
    'taskToken' => 'qwertyuiop',
]));
```

### Mark as task as successful

```php
use AsyncAws\StepFunctions\Input\SendTaskSuccessInput;
use AsyncAws\StepFunctions\StepFunctionsClient;

$stepFunctions = new StepFunctionsClient();

$result = $stepFunctions->sendTaskSuccess(new SendTaskSuccessInput([
    'taskToken' => 'qwertyuiop',
    'output' => '{"success": ":partyparrot:"}',
]));
```

### Mark as task as failed

```php
use AsyncAws\StepFunctions\Input\SendTaskFailureInput;
use AsyncAws\StepFunctions\StepFunctionsClient;

$stepFunctions = new StepFunctionsClient();

$result = $stepFunctions->sendTaskSuccess(new SendTaskFailureInput([
    'taskToken' => 'qwertyuiop',
    'error' => 'err_foo',
    'cause' => 'Crash!',
]));
```
