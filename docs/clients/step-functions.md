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
