<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Ses\Input\Body;
use AsyncAws\Ses\Input\Content;
use AsyncAws\Ses\Input\Destination;
use AsyncAws\Ses\Input\EmailContent;
use AsyncAws\Ses\Input\Message;
use AsyncAws\Ses\Input\MessageTag;
use AsyncAws\Ses\Input\RawMessage;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\Input\Template;
use PHPUnit\Framework\TestCase;

class SendEmailRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new SendEmailRequest([
            'FromEmailAddress' => 'change me',
            'Destination' => new Destination([
                'ToAddresses' => ['change me'],
                'CcAddresses' => ['change me'],
                'BccAddresses' => ['change me'],
            ]),
            'ReplyToAddresses' => ['change me'],
            'FeedbackForwardingEmailAddress' => 'change me',
            'Content' => new EmailContent([
                'Simple' => new Message([
                    'Subject' => new Content([
                        'Data' => 'change me',
                        'Charset' => 'change me',
                    ]),
                    'Body' => new Body([
                        'Text' => new Content([
                            'Data' => 'change me',
                            'Charset' => 'change me',
                        ]),
                        'Html' => new Content([
                            'Data' => 'change me',
                            'Charset' => 'change me',
                        ]),
                    ]),
                ]),
                'Raw' => new RawMessage([
                    'Data' => 'change me',
                ]),
                'Template' => new Template([
                    'TemplateArn' => 'change me',
                    'TemplateData' => 'change me',
                ]),
            ]),
            'EmailTags' => [new MessageTag([
                'Name' => 'change me',
                'Value' => 'change me',
            ])],
            'ConfigurationSetName' => 'change me',
        ]);

        $expected = '{"change": "it"}';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
