<?php

namespace AsyncAws\Ses\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\GetSuppressedDestinationRequest;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\Result\GetSuppressedDestinationResponse;
use AsyncAws\Ses\Result\SendEmailResponse;
use AsyncAws\Ses\SesClient;
use AsyncAws\Ses\ValueObject\Body;
use AsyncAws\Ses\ValueObject\Content;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\Message;
use AsyncAws\Ses\ValueObject\RawMessage;
use AsyncAws\Ses\ValueObject\Template;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SesClientTest extends TestCase
{
    public function testGetSuppressedDestination(): void
    {
        $client = new SesClient([], new NullProvider(), new MockHttpClient());

        $input = new GetSuppressedDestinationRequest([
            'EmailAddress' => 'test@example.org',
        ]);
        $result = $client->getSuppressedDestination($input);

        self::assertInstanceOf(GetSuppressedDestinationResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSendEmail(): void
    {
        $client = new SesClient([], new NullProvider(), new MockHttpClient());

        $input = new SendEmailRequest([
            'Destination' => new Destination([
                'ToAddresses' => ['change me'],
                'CcAddresses' => ['change me'],
                'BccAddresses' => ['change me'],
            ]),

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
        ]);
        $result = $client->SendEmail($input);

        self::assertInstanceOf(SendEmailResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSendMessage()
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options): ResponseInterface {
            self::assertSame('POST', $method);
            self::assertStringStartsWith('https://email.eu-west-1.amazonaws.com:8984/', $url);

            $content = json_decode(StreamFactory::create($options['body'])->stringify(), true);

            self::assertSame('Hello!', $content['Content']['Simple']['Subject']['Data']);
            self::assertSame('tobias.nyholm@gmail.com', $content['Destination']['ToAddresses'][0]);
            self::assertSame('foo@test.se', $content['FromEmailAddress']);
            self::assertSame('Hello There!', $content['Content']['Simple']['Body']['Text']['Data']);

            $json = '{"MessageId":"foobar"}';

            return new MockResponse($json, [
                'http_code' => 200,
            ]);
        });

        $ses = new SesClient([
            'endpoint' => 'https://email.eu-west-1.amazonaws.com:8984',
        ], new NullProvider(), $httpClient);

        $input = new SendEmailRequest([
            'FromEmailAddress' => 'foo@test.se',
        ]);
        $input->setDestination(new Destination([
            'ToAddresses' => ['tobias.nyholm@gmail.com'],
        ]));
        $input->setContent(new EmailContent([
            'Simple' => new Message([
                'Subject' => ['Data' => 'Hello!'],
                'Body' => ['Text' => ['Data' => 'Hello There!']],
            ]),
        ]));
        $result = $ses->sendEmail($input);

        self::assertEquals('foobar', $result->getMessageId());
    }

    public function testSendThrowsForErrorResponse()
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options): ResponseInterface {
            $json = '{"message":"Missing final \'@domain\'"}';

            return new MockResponse($json, [
                'http_code' => 400,
            ]);
        });
        $ses = new SesClient([], new NullProvider(), $httpClient);

        $input = new SendEmailRequest([
            'FromEmailAddress' => 'foo', // no @test.se
        ]);
        $input->setDestination(new Destination([
            'ToAddresses' => ['tobias.nyholm@gmail.com'],
        ]));
        $input->setContent(new EmailContent([
            'Simple' => new Message([
                'Subject' => ['Data' => 'Hello!'],
                'Body' => ['Text' => ['Data' => 'Hello There!']],
            ]),
        ]));
        $result = $ses->sendEmail($input);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('HTTP 400 returned');
        $result->resolve();
    }

    /**
     * Make sure that SignService is "ses".
     */
    public function testSignService()
    {
        $ses = new SesClient([], new NullProvider());
        $refl = new \ReflectionClass($ses);
        $method = $refl->getMethod('getEndpointMetadata');
        if (\PHP_VERSION_ID < 80100) {
            $method->setAccessible(true);
        }
        $data = $method->invokeArgs($ses, ['eu-central-1']);

        self::assertEquals('ses', $data['signService']);
    }
}
