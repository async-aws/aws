---
layout: client
category: clients
name: SES
package: async-aws/ses
---

## Usage

### Send a message

```php
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\SesClient;
use AsyncAws\Ses\ValueObject\Body;
use AsyncAws\Ses\ValueObject\Content;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\Message;

$ses = new SesClient();

$result = $ses->sendEmail(new SendEmailRequest([
    'FromEmailAddress' => 'invoice-bot@my-company.com',
    'Content' => new EmailContent([
        'Simple' => new Message([
            'Subject' => new Content(['Data' => 'New Invoice']),
            'Body' => new Body([
                'Text' => new Content(['Data' => 'A new invoice is available']),
            ]),
        ]),
    ]),
    'Destination' => new Destination([
        'ToAddresses' => ['customer@customer-company.com']
    ]),
]));

echo $result->getMessageId();
```
