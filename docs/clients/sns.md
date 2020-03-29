---
layout: client
category: clients
---

# SNS Client

## Examples

### Send a message

```php
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\SnsClient;

$sns = new SnsClient();

$result = $sns->publish(new PublishInput([
    'TopicArn' => 'arn:aws:sns:us-east-1:46563727:purshase',
    'Message' => 'New Purchase order',
]));

echo $result->getMessageId();
```
