<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Input\CancelQueryRequest;
use AsyncAws\TimestreamQuery\Input\PrepareQueryRequest;
use AsyncAws\TimestreamQuery\Input\QueryRequest;
use AsyncAws\TimestreamQuery\Result\CancelQueryResponse;
use AsyncAws\TimestreamQuery\Result\PrepareQueryResponse;
use AsyncAws\TimestreamQuery\Result\QueryResponse;
use AsyncAws\TimestreamQuery\TimestreamQueryClient;
use Symfony\Component\HttpClient\MockHttpClient;

class TimestreamQueryClientTest extends TestCase
{
    public function testCancelQuery(): void
    {
        $client = new TimestreamQueryClient([], new NullProvider(), new MockHttpClient());

        $input = new CancelQueryRequest([
            'QueryId' => 'qwertyuiop',
        ]);
        $result = $client->cancelQuery($input);

        self::assertInstanceOf(CancelQueryResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPrepareQuery(): void
    {
        $client = new TimestreamQueryClient([], new NullProvider(), new MockHttpClient());

        $input = new PrepareQueryRequest([
            'QueryString' => 'SELECT * FROM db.tbl ORDER BY time DESC LIMIT 10',
            'ValidateOnly' => true,

        ]);
        $result = $client->prepareQuery($input);

        self::assertInstanceOf(PrepareQueryResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testQuery(): void
    {
        $client = new TimestreamQueryClient([], new NullProvider(), new MockHttpClient());

        $input = new QueryRequest([
            'ClientToken' => 'qwertyuiop',
            'QueryString' => 'SELECT * FROM db.tbl ORDER BY time DESC LIMIT 10',

        ]);
        $result = $client->query($input);

        self::assertInstanceOf(QueryResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
