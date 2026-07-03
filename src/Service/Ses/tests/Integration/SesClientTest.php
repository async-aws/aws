<?php

namespace AsyncAws\Ses\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\CreateEmailIdentityRequest;
use AsyncAws\Ses\Input\DeleteSuppressedDestinationRequest;
use AsyncAws\Ses\Input\GetEmailIdentityRequest;
use AsyncAws\Ses\Input\GetSuppressedDestinationRequest;
use AsyncAws\Ses\Input\PutEmailIdentityDkimSigningAttributesRequest;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\SesClient;
use AsyncAws\Ses\ValueObject\Body;
use AsyncAws\Ses\ValueObject\Content;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\Message;
use AsyncAws\Ses\ValueObject\MessageTag;
use AsyncAws\Ses\ValueObject\RawMessage;
use AsyncAws\Ses\ValueObject\Tag;
use AsyncAws\Ses\ValueObject\Template;

class SesClientTest extends TestCase
{
    public function testCreateEmailIdentity(): void
    {
        $client = $this->getClient();

        $input = new CreateEmailIdentityRequest([
            'EmailIdentity' => 'change me',
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
            'DkimSigningAttributes' => new DkimSigningAttributes([
                'DomainSigningSelector' => 'change me',
                'DomainSigningPrivateKey' => 'change me',
                'NextSigningKeyLength' => 'change me',
                'DomainSigningAttributesOrigin' => 'change me',
            ]),
            'ConfigurationSetName' => 'change me',
        ]);
        $result = $client->createEmailIdentity($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getIdentityType());
        self::assertFalse($result->getVerifiedForSendingStatus());
        // self::assertTODO(expected, $result->getDkimAttributes());
    }

    public function testDeleteSuppressedDestination(): void
    {
        $client = $this->getClient();

        $input = new DeleteSuppressedDestinationRequest([
            'EmailAddress' => 'test@example.com',
        ]);
        $result = $client->deleteSuppressedDestination($input);

        $result->resolve();
    }

    public function testGetEmailIdentity(): void
    {
        $client = $this->getClient();

        $input = new GetEmailIdentityRequest([
            'EmailIdentity' => 'change me',
        ]);
        $result = $client->getEmailIdentity($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getIdentityType());
        self::assertFalse($result->getFeedbackForwardingStatus());
        self::assertFalse($result->getVerifiedForSendingStatus());
        // self::assertTODO(expected, $result->getDkimAttributes());
        // self::assertTODO(expected, $result->getMailFromAttributes());
        // self::assertTODO(expected, $result->getPolicies());
        // self::assertTODO(expected, $result->getTags());
        self::assertSame('changeIt', $result->getConfigurationSetName());
        self::assertSame('changeIt', $result->getVerificationStatus());
        // self::assertTODO(expected, $result->getVerificationInfo());
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

    public function testPutEmailIdentityDkimSigningAttributes(): void
    {
        $client = $this->getClient();

        $input = new PutEmailIdentityDkimSigningAttributesRequest([
            'EmailIdentity' => 'change me',
            'SigningAttributesOrigin' => 'change me',
            'SigningAttributes' => new DkimSigningAttributes([
                'DomainSigningSelector' => 'change me',
                'DomainSigningPrivateKey' => 'change me',
                'NextSigningKeyLength' => 'change me',
                'DomainSigningAttributesOrigin' => 'change me',
            ]),
        ]);
        $result = $client->putEmailIdentityDkimSigningAttributes($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getDkimStatus());
        // self::assertTODO(expected, $result->getDkimTokens());
        self::assertSame('changeIt', $result->getSigningHostedZone());
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
