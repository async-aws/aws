---
layout: client
category: clients
name: Sns
package: async-aws/sns
---

## Usage

### Send a message

```php
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\SnsClient;

$sns = new SnsClient();

$result = $sns->publish(new PublishInput([
    'TopicArn' => 'arn:aws:sns:us-east-1:46563727:purchase',
    'Message' => 'New Purchase order',
]));

echo $result->getMessageId();
```
