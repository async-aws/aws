<?php

namespace AsyncAws\Athena\Tests\Unit;

use AsyncAws\Athena\Input\GetQueryExecutionInput;
use AsyncAws\Athena\Input\StartQueryExecutionInput;
use AsyncAws\Athena\Result\GetQueryExecutionOutput;
use AsyncAws\Athena\Result\StartQueryExecutionOutput;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AthenaClientTest extends TestCase
{
    public function testGetQueryExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetQueryExecutionInput([
            'QueryExecutionId' => 'change me',
        ]);
        $result = $client->GetQueryExecution($input);

        self::assertInstanceOf(GetQueryExecutionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartQueryExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new StartQueryExecutionInput([
            'QueryString' => 'change me',

        ]);
        $result = $client->StartQueryExecution($input);

        self::assertInstanceOf(StartQueryExecutionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
