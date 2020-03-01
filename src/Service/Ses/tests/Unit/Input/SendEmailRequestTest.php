<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\Body;
use AsyncAws\Ses\Input\Content;
use AsyncAws\Ses\Input\Destination;
use AsyncAws\Ses\Input\EmailContent;
use AsyncAws\Ses\Input\Message;
use AsyncAws\Ses\Input\SendEmailRequest;

class SendEmailRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new SendEmailRequest([
            'FromEmailAddress' => 'jeremy@derusse.com',
            'Destination' => new Destination([
                'ToAddresses' => ['recipient1@example.com', 'recipient2@example.com'],
                'CcAddresses' => ['recipient3@example.com'],
                'BccAddresses' => [],
            ]),
            'ReplyToAddresses' => [],
            'Content' => new EmailContent([
                'Simple' => new Message([
                    'Subject' => new Content([
                        'Data' => 'Test email',
                        'Charset' => 'UTF-8',
                    ]),
                    'Body' => new Body([
                        'Text' => new Content([
                            'Data' => 'This is the message body in text format.',
                            'Charset' => 'UTF-8',
                        ]),
                        'Html' => new Content([
                            'Data' => 'This message body contains HTML formatting. It can, for example, contain links like this one: <a class="ulink" href="http://docs.aws.amazon.com/ses/latest/DeveloperGuide" target="_blank">Amazon SES Developer Guide</a>.',
                            'Charset' => 'UTF-8',
                        ]),
                    ]),
                ]),
            ]),
        ]);

        // see example-1.json from SDK
        $expected = '{
            "FromEmailAddress": "jeremy@derusse.com",
            "Destination": {
                "ToAddresses": [
                    "recipient1@example.com",
                    "recipient2@example.com"
                ],
                "CcAddresses": [
                    "recipient3@example.com"
                ]
            },
            "Content": {
                "Simple": {
                    "Subject": {
                        "Data": "Test email",
                        "Charset": "UTF-8"
                    },
                    "Body": {
                        "Text": {
                            "Data": "This is the message body in text format.",
                            "Charset": "UTF-8"
                        },
                        "Html": {
                            "Data": "This message body contains HTML formatting. It can, for example, contain links like this one: <a class=\\"ulink\\" href=\\"http:\\/\\/docs.aws.amazon.com\\/ses\\/latest\\/DeveloperGuide\\" target=\\"_blank\\">Amazon SES Developer Guide<\\/a>.",
                            "Charset": "UTF-8"
                        }
                    }
                }
            }
        }';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
