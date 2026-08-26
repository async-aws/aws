---
layout: client
category: clients
name: BedrockAgentCore
package: async-aws/bedrock-agent-core
---

## Usage

### Invoke an agent runtime

```php
use AsyncAws\BedrockAgentCore\BedrockAgentCoreClient;
use AsyncAws\BedrockAgentCore\Input\InvokeAgentRuntimeRequest;

$bedrockAgentCore = new BedrockAgentCoreClient();

$request = new InvokeAgentRuntimeRequest([
    'agentRuntimeArn' => 'arn:aws:bedrock-agentcore:eu-west-1:965624758642:runtime/my-agent',
    'qualifier' => 'DEFAULT',
    'contentType' => 'application/json',
    'accept' => 'application/json',
    'payload' => json_encode(['prompt' => 'Write me a love poem.']),
]);

$result = $bedrockAgentCore->invokeAgentRuntime($request);

echo $result->getResponse() . PHP_EOL;

```

Pass a `runtimeSessionId` to keep several invocations in the same conversation:

```php
use AsyncAws\BedrockAgentCore\BedrockAgentCoreClient;
use AsyncAws\BedrockAgentCore\Input\InvokeAgentRuntimeRequest;

$bedrockAgentCore = new BedrockAgentCoreClient();

$sessionId = 'ba8dc2f0-3b3f-4f4a-9a1e-9c0d3f2b7a11';

$result = $bedrockAgentCore->invokeAgentRuntime(new InvokeAgentRuntimeRequest([
    'agentRuntimeArn' => 'arn:aws:bedrock-agentcore:eu-west-1:965624758642:runtime/my-agent',
    'runtimeSessionId' => $sessionId,
    'payload' => json_encode(['prompt' => 'And now a haiku.']),
]));

echo $result->getRuntimeSessionId() . PHP_EOL;
echo $result->getResponse() . PHP_EOL;

```
See [`InvokeAgentRuntime`](https://docs.aws.amazon.com/bedrock-agentcore/latest/APIReference/API_InvokeAgentRuntime.html) for more information.
