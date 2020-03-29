---
layout: client
category: clients
---

# SNS Client

## Examples

### Send a message

```php
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\Input\SendMessageRequest;
use AsyncAws\Sqs\SqsClient;

$sqs = new SqsClient();

$queueUrl = $sqs->getQueueUrl(new GetQueueUrlRequest([
    'QueueName' => 'invoices-queue',
]))->getQueueUrl();

$result = $sqs->sendMessage(new SendMessageRequest([
    'QueueUrl' => $queueUrl,
    'MessageBody' => 'invoiceId: 1337',
]));

echo $result->getMessageId();
```

### Receive Messages

```php
use AsyncAws\Sqs\Input\ChangeMessageVisibilityRequest;
use AsyncAws\Sqs\Input\DeleteMessageRequest;
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\Input\ReceiveMessageRequest;
use AsyncAws\Sqs\SqsClient;

$sqs = new SqsClient();

$queueUrl = $sqs->getQueueUrl(new GetQueueUrlRequest([
    'QueueName' => 'invoices-queue',
]))->getQueueUrl();

$result = $sqs->receiveMessage(new ReceiveMessageRequest([
    'QueueUrl' => $queueUrl,
    'WaitTimeSeconds' => 20,
    'MaxNumberOfMessages' => 5,
]));
foreach ($result->getMessages() as $message) {
    try {
        // Do something with message
        // ...

        // When finished, delete the message
        $sqs->deleteMessage(new DeleteMessageRequest([
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $message->getReceiptHandle(),
        ]));
    } catch (\Exception $e) {
        // Optional : Set the visibility to 0 to be instantaneously requeued
        $sqs->changeMessageVisibility(new ChangeMessageVisibilityRequest([
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $message->getReceiptHandle(),
            'VisibilityTimeout' => 0,
        ]));
    }
}
```
