<?php

namespace AsyncAws\Athena\Tests\Integration;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\GetQueryExecutionInput;
use AsyncAws\Athena\Input\StartQueryExecutionInput;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class AthenaClientTest extends TestCase
{
    public function testGetQueryExecution(): void
    {
        $client = $this->getClient();

        $input = new GetQueryExecutionInput([
            'QueryExecutionId' => 'change me',
        ]);
        $result = $client->GetQueryExecution($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getQueryExecution());
    }

    public function testStartQueryExecution(): void
    {
        $client = $this->getClient();

        $input = new StartQueryExecutionInput([
            'QueryString' => 'change me',
            'ClientRequestToken' => 'change me',
            'QueryExecutionContext' => new QueryExecutionContext([
                'Database' => 'change me',
                'Catalog' => 'change me',
            ]),
            'ResultConfiguration' => new ResultConfiguration([
                'OutputLocation' => 'change me',
                'EncryptionConfiguration' => new EncryptionConfiguration([
                    'EncryptionOption' => 'change me',
                    'KmsKey' => 'change me',
                ]),
            ]),
            'WorkGroup' => 'change me',
        ]);
        $result = $client->StartQueryExecution($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getQueryExecutionId());
    }

    private function getClient(): AthenaClient
    {
        self::fail('Not implemented');

        return new AthenaClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
