<?php

namespace AsyncAws\Ses\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\DeleteSuppressedDestinationRequest;
use AsyncAws\Ses\Input\GetSuppressedDestinationRequest;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\SesClient;
use AsyncAws\Ses\ValueObject\Body;
use AsyncAws\Ses\ValueObject\Content;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\Message;
use AsyncAws\Ses\ValueObject\MessageTag;
use AsyncAws\Ses\ValueObject\RawMessage;
use AsyncAws\Ses\ValueObject\Template;

class SesClientTest extends TestCase
{
    public function testDeleteSuppressedDestination(): void
    {
        $client = $this->getClient();

        $input = new DeleteSuppressedDestinationRequest([
            'EmailAddress' => 'test@example.com',
        ]);
        $result = $client->deleteSuppressedDestination($input);

        $result->resolve();
    }

    public function testGetSuppressedDestination(): void
    {
        $client = $this->getClient();

        $input = new GetSuppressedDestinationRequest([
            'EmailAddress' => 'test@example.com',
        ]);
        $result = $client->getSuppressedDestination($input);

        $result->resolve();

        $dest = $result->getSuppressedDestination();

        self::assertSame('test@example.com', $dest->getEmailAddress());
    }

    public function testSendEmail(): void
    {
        $client = $this->getClient();

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
        $result = $client->SendEmail($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getMessageId());
    }

    private function getClient(): SesClient
    {
        self::markTestSkipped('No Docker image for SES');

        return new SesClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
