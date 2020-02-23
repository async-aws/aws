<?php

namespace AsyncAws\Sns\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\Result\PublishResponse;
use AsyncAws\Sns\SnsClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SnsClientTest extends TestCase
{
    public function testPublish(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new PublishInput([

            'Message' => 'change me',

        ]);
        $result = $client->Publish($input);

        self::assertInstanceOf(PublishResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
