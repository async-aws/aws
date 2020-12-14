<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Mail\Tests\Unit\Transport;

use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Illuminate\Mail\Transport\AsyncAwsSesTransport;
use AsyncAws\Ses\Result\SendEmailResponse;
use AsyncAws\Ses\SesClient;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\EmailContent;
use PHPUnit\Framework\TestCase;

class AsyncAwsSesTransportTest extends TestCase
{
    public function testSend()
    {
        $message = new \Swift_Message();
        $message->setTo('bob@example.com', 'Bob');
        $message->setFrom('alice@example.com', 'Alice');
        $message->setSubject('Important message');
        $message->setBody('This is my secret: 123');

        $validateInput = function (array $input) {
            if (!isset($input['Content']) || !$input['Content'] instanceof EmailContent) {
                return false;
            }

            if (!isset($input['Destination']) || !$input['Destination'] instanceof Destination) {
                return false;
            }

            $destination = $input['Destination'];
            if (!\in_array('bob@example.com', $destination->getToAddresses())) {
                return false;
            }

            return true;
        };

        // The return value from SesClient::sendEmail()
        $result = ResultMockFactory::create(SendEmailResponse::class, [
            'MessageId' => 'message-result-id',
        ]);

        $ses = $this->getMockBuilder(SesClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sendEmail'])
            ->getMock();

        $ses->expects(self::once())
            ->method('sendEmail')
            ->with(self::callback($validateInput))
            ->willReturn($result);

        $transport = new AsyncAwsSesTransport($ses, ['@region' => 'eu-central-1']);
        self::assertEquals(1, $transport->send($message));
        self::assertTrue($message->getHeaders()->has('X-SES-Message-ID'));
        self::assertEquals('message-result-id', $message->getHeaders()->get('X-SES-Message-ID')->getValue());
    }

    public function testOptions()
    {
        $options = ['foo' => 'bar'];
        $transport = new AsyncAwsSesTransport(new SesClient(), $options);
        self::assertEquals($options, $transport->getOptions());

        $newOptions = ['foo2' => 'Biz'];
        $transport->setOptions($newOptions);
        self::assertEquals($newOptions, $transport->getOptions());
    }
}
