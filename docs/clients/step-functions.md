---
layout: client
category: clients
name: StepFunctions
package: async-aws/step-functions
---

## Usage

### Start a state machine execution

```php
use AsyncAws\Lambda\LambdaClient;

$stepFunctions = new StepFunctionsClient();

$result = $stepFunctions->startExecution([
    'stateMachineArn' => 'arn:sfn',
    'input' => '{"foo": "bar"}',
]);

echo $result->getExecutionArn();
```
