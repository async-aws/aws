<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Ses\Input\Body;
use AsyncAws\Ses\Input\Content;
use AsyncAws\Ses\Input\Destination;
use AsyncAws\Ses\Input\EmailContent;
use AsyncAws\Ses\Input\Message;
use AsyncAws\Ses\Input\MessageTag;
use AsyncAws\Ses\Input\SendEmailRequest;
use PHPUnit\Framework\TestCase;

class SendEmailRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new SendEmailRequest([
            'FromEmailAddress' => 'jeremy@derusse.com',
            'Destination' => new Destination([
                'ToAddresses' => ['jeremy+to@derusse.com'],
                'CcAddresses' => ['jeremy+cc@derusse.'],
                'BccAddresses' => ['jeremy+bcc@derusse.'],
            ]),
            'ReplyToAddresses' => ['jeremy+reply@derusse.com'],
            'Content' => new EmailContent([
                'Simple' => new Message([
                    'Subject' => new Content([
                        'Data' => 'email subject',
                        'Charset' => 'utf-8',
                    ]),
                    'Body' => new Body([
                        'Text' => new Content([
                            'Data' => 'email body',
                            'Charset' => 'utf-8',
                        ]),
                    ]),
                ]),
            ]),
            'EmailTags' => [new MessageTag([
                'Name' => 'team',
                'Value' => 'engineering',
            ])],
        ]);

        /** Used aws-sdk to generate the output */
        $expected = '{
            "Action": "SendEmail",
            "Version": "2019-09-27",
            "FromEmailAddress": "jeremy@derusse.com",
            "Destination": {
                "ToAddresses": [
                    "jeremy+to@derusse.com"
                ],
                "CcAddresses": [
                    "jeremy+cc@derusse."
                ],
                "BccAddresses": [
                    "jeremy+bcc@derusse."
                ]
            },
            "ReplyToAddresses": [
                "jeremy+reply@derusse.com"
            ],
            "Content": {
                "Simple": {
                    "Subject": {
                        "Data": "email subject",
                        "Charset": "utf-8"
                    },
                    "Body": {
                        "Text": {
                            "Data": "email body",
                            "Charset": "utf-8"
                        }
                    }
                }
            },
            "EmailTags": [
                {
                  "Name": "team",
                  "Value": "engineering"
                }
            ]
        }';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
