<?php

declare(strict_types=1);

namespace AsyncAws\Ses\Tests\Integration;

use AsyncAws\Ses\Input\Destination;
use AsyncAws\Ses\Input\EmailContent;
use AsyncAws\Ses\Input\Message;
use AsyncAws\Ses\Input\SendEmailRequest;
use PHPUnit\Framework\TestCase;

class SesClientTest extends TestCase
{
    use GetClient;

    public function testSendMessage()
    {
        $sqs = $this->getClient();

        $input = new SendEmailRequest([
            'FromEmailAddress' =>'foo@test.se'
        ]);
        $input->setDestination(new Destination([
            'ToAddresses' => ['tobias.nyholm@gmail.com'],
        ]));
        $input->setContent(new EmailContent([
            'Simple' => new Message([
                'Subject' => ['Data' => 'hello'],
                'Body' => ['Text' => ['Data' => 'Body friends']],
            ])
        ]));
        $result = $sqs->sendEmail($input);

        self::assertNotNull($result->getMessageId());
    }
}
