<?php

namespace AsyncAws\TimestreamQuery\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Input\CancelQueryRequest;
use AsyncAws\TimestreamQuery\Input\DescribeEndpointsRequest;
use AsyncAws\TimestreamQuery\Input\PrepareQueryRequest;
use AsyncAws\TimestreamQuery\Input\QueryRequest;
use AsyncAws\TimestreamQuery\TimestreamQueryClient;

class TimestreamQueryClientTest extends TestCase
{
    public function testCancelQuery(): void
    {
        self::markTestIncomplete('Cannot test without support for timestream.');

        $client = $this->getClient();

        $input = new CancelQueryRequest([
            'QueryId' => 'change me',
        ]);
        $result = $client->cancelQuery($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getCancellationMessage());
    }

    public function testDescribeEndpoints(): void
    {
        self::markTestIncomplete('Cannot test without support for timestream.');

        $client = $this->getClient();

        $input = new DescribeEndpointsRequest([

        ]);
        $result = $client->describeEndpoints($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getEndpoints());
    }

    public function testPrepareQuery(): void
    {
        self::markTestIncomplete('Cannot test without support for timestream.');

        $client = $this->getClient();

        $input = new PrepareQueryRequest([
            'QueryString' => 'change me',
            'ValidateOnly' => false,
        ]);
        $result = $client->prepareQuery($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getQueryString());
        // self::assertTODO(expected, $result->getColumns());
        // self::assertTODO(expected, $result->getParameters());
    }

    public function testQuery(): void
    {
        self::markTestIncomplete('Cannot test without support for timestream.');

        $client = $this->getClient();

        $input = new QueryRequest([
            'QueryString' => 'change me',
            'ClientToken' => 'change me',
            'NextToken' => 'change me',
            'MaxRows' => 1337,
        ]);
        $result = $client->query($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getQueryId());
        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getRows());
        // self::assertTODO(expected, $result->getColumnInfo());
        // self::assertTODO(expected, $result->getQueryStatus());
    }

    private function getClient(): TimestreamQueryClient
    {
        self::fail('Not implemented');

        return new TimestreamQueryClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
