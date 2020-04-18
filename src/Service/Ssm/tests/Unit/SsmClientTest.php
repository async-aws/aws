<?php

namespace AsyncAws\Ssm\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\PutParameterResult;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\PutParameterRequest;
use Symfony\Component\HttpClient\MockHttpClient;

class SsmClientTest extends TestCase
{
    public function testPutParameter(): void
    {
        $client = new SsmClient([], new NullProvider(), new MockHttpClient());

        $input = new PutParameterRequest([
            'Name' => 'change me',

            'Value' => 'change me',
            'Type' => 'change me',

        ]);
        $result = $client->PutParameter($input);

        self::assertInstanceOf(PutParameterResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
