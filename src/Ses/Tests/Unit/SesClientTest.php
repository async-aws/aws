<?php

namespace AsyncAws\Ses\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Ses\Input\Body;
use AsyncAws\Ses\Input\Content;
use AsyncAws\Ses\Input\Destination;
use AsyncAws\Ses\Input\EmailContent;
use AsyncAws\Ses\Input\Message;
use AsyncAws\Ses\Input\RawMessage;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\Input\Template;
use AsyncAws\Ses\Result\SendEmailResponse;
use AsyncAws\Ses\SesClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SesClientTest extends TestCase
{
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
            $this->assertSame('POST', $method);
            $this->assertStringStartsWith('https://email.eu-west-1.amazonaws.com:8984/', $url);

            $content = json_decode(StreamFactory::create($options['body'])->stringify(), true);

            $this->assertSame('Hello!', $content['Content']['Simple']['Subject']['Data']);
            $this->assertSame('tobias.nyholm@gmail.com', $content['Destination']['ToAddresses'][0]);
            $this->assertSame('foo@test.se', $content['FromEmailAddress']);
            $this->assertSame('Hello There!', $content['Content']['Simple']['Body']['Text']['Data']);

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
}
