---
layout: client
category: clients
name: BedrockRuntime
package: async-aws/bedrock-runtime
---

## Usage

### InvokeModel

```php
use AsyncAws\BedrockRuntime\BedrockRuntimeClient;
use AsyncAws\BedrockRuntime\Input\InvokeModelRequest;

$bedrockRuntime = new BedrockRuntimeClient();

$body = [
    'anthropic_version' => 'bedrock-2023-05-31',
    'max_tokens' => 4096,
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                ['type' => 'text', 'text' => 'Write me a love poem.']
            ]
        ]
    ],
    'temperature' => 1
];

$result = $bedrockRuntime->invokeModel(new InvokeModelRequest([
    'modelId' => 'us.anthropic.claude-3-7-sonnet-20250219-v1:0',
    'contentType' => 'application/json',
    'body' => json_encode($body, JSON_THROW_ON_ERROR)
]));

$response = json_decode($result->getBody(), true, 512, JSON_THROW_ON_ERROR);

echo $response['content'][0]['text'];

```
more information [InvokeModel](https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_InvokeModel.html)

### Converse

```php
use AsyncAws\BedrockRuntime\BedrockRuntimeClient;
use AsyncAws\BedrockRuntime\Input\ConverseRequest;
use AsyncAws\BedrockRuntime\ValueObject\ContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\Message;

$bedrockRuntime = new BedrockRuntimeClient();

$result = $bedrockRuntime->converse(new ConverseRequest([
    'modelId' => 'us.anthropic.claude-3-7-sonnet-20250219-v1:0',
    'messages' => [new Message([
        'role' => 'user',
        'content' => [new ContentBlock([
            'text' => 'Write me a love poem.'
        ])],
    ])]
]));

echo $result->getOutput()->getMessage()->getContent()[0]->getText();

```
more information [Converse](https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html)
