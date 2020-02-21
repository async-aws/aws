<?php

declare(strict_types=1);

namespace AsyncAws\Ses\Tests\Unit;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Ses\Input\Destination;
use AsyncAws\Ses\Input\EmailContent;
use AsyncAws\Ses\Input\Message;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\SesClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Mailer\Bridge\Amazon\Transport\SesApiTransport;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SesClientTest extends TestCase
{
    public function testSendMessage()
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options): ResponseInterface {
            $this->assertSame('POST', $method);
            $this->assertStringStartsWith('https://email.eu-west-1.amazonaws.com:8984/', $url);

            parse_str($options['body'], $content);

            $this->assertSame('Hello!', $content['Content_Simple_Subject_Data']);
            $this->assertSame('tobias.nyholm@gmail.com', $content['Destination_ToAddresses_1']);
            $this->assertSame('foo@test.se', $content['FromEmailAddress']);
            $this->assertSame('Hello There!', $content['Content_Simple_Body_Text_Data']);

            $xml = '<SendEmailResponse xmlns="https://email.amazonaws.com/doc/2010-03-31/">
  <SendEmailResult>
    <MessageId>foobar</MessageId>
  </SendEmailResult>
</SendEmailResponse>';

            return new MockResponse($xml, [
                'http_code' => 200,
            ]);
        });

        $ses = new SesClient([
            'endpoint' => 'https://email.eu-west-1.amazonaws.com:8984',
        ], null, $httpClient);

        $input = new SendEmailRequest([
            'FromEmailAddress' =>'foo@test.se'
        ]);
        $input->setDestination(new Destination([
            'ToAddresses' => ['tobias.nyholm@gmail.com'],
        ]));
        $input->setContent(new EmailContent([
            'Simple' => new Message([
                'Subject' => ['Data' => 'Hello!'],
                'Body' => ['Text' => ['Data' => 'Hello There!']],
            ])
        ]));
        $result = $ses->sendEmail($input);

        self::assertEquals('foobar', $result->getMessageId());
    }


    public function testSendThrowsForErrorResponse()
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options): ResponseInterface {
            $xml = "<SendEmailResponse xmlns=\"https://email.amazonaws.com/doc/2010-03-31/\">
                <Error>
                    <Message>i'm a teapot</Message>
                    <Code>418</Code>
                </Error>
            </SendEmailResponse>";

            return new MockResponse($xml, [
                'http_code' => 418,
            ]);
        });
        $ses = new SesClient([], null, $httpClient);

        $input = new SendEmailRequest([
            'FromEmailAddress' =>'foo@test.se'
        ]);
        $input->setDestination(new Destination([
            'ToAddresses' => ['tobias.nyholm@gmail.com'],
        ]));
        $input->setContent(new EmailContent([
            'Simple' => new Message([
                'Subject' => ['Data' => 'Hello!'],
                'Body' => ['Text' => ['Data' => 'Hello There!']],
            ])
        ]));
        $result = $ses->sendEmail($input);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('HTTP 418 returned');
        $result->resolve();
    }
}
