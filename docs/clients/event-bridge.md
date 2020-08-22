---
layout: client
category: clients
name: EventBridge
package: async-aws/event-bridge
---

## Usage

### Sends custom events

```php
use AsyncAws\EventBridge\EventBridgeClient;
use AsyncAws\EventBridge\Input\PutEventsRequest;
use AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry;

$eventBridge = new EventBridgeClient();

$events = $eventBridge->putEvents(new PutEventsRequest([
    'Entries' => [
        new PutEventsRequestEntry([
            'EventBusName' => 'marketing',
            'Source' => 'acme.newsletter.campaign',
            'DetailType' => 'UserSignUp',
            'Detail' => json_encode(['email' => $userEmail]),
        ])
    ],
]));

echo 'Sent '. count($events->getEntries()) . ' events, with ' . $events->getFailedEntryCount() . ' failures';
```
