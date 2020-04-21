<?php

namespace AsyncAws\Ssm\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\DeleteParameterRequest;
use AsyncAws\Ssm\Input\GetParameterRequest;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\Input\GetParametersRequest;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\ParameterStringFilter;
use AsyncAws\Ssm\ValueObject\Tag;

class SsmClientTest extends TestCase
{
    public function testDeleteParameter(): void
    {
        $client = $this->getClient();

        $input = new DeleteParameterRequest([
            'Name' => 'change me',
        ]);
        $result = $client->DeleteParameter($input);

        $result->resolve();
    }

    public function testGetParameter(): void
    {
        $client = $this->getClient();

        $input = new GetParameterRequest([
            'Name' => 'change me',
            'WithDecryption' => false,
        ]);
        $result = $client->GetParameter($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getParameter());
    }

    public function testGetParameters(): void
    {
        $client = $this->getClient();

        $input = new GetParametersRequest([
            'Names' => ['change me'],
            'WithDecryption' => false,
        ]);
        $result = $client->GetParameters($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getParameters());
        // self::assertTODO(expected, $result->getInvalidParameters());
    }

    public function testGetParametersByPath(): void
    {
        $client = $this->getClient();

        $input = new GetParametersByPathRequest([
            'Path' => 'change me',
            'Recursive' => false,
            'ParameterFilters' => [new ParameterStringFilter([
                'Key' => 'change me',
                'Option' => 'change me',
                'Values' => ['change me'],
            ])],
            'WithDecryption' => false,
            'MaxResults' => 1337,
            'NextToken' => 'change me',
        ]);
        $result = $client->GetParametersByPath($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getParameters());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testPutParameter(): void
    {
        $client = $this->getClient();

        $input = new PutParameterRequest([
            'Name' => 'change me',
            'Description' => 'change me',
            'Value' => 'change me',
            'Type' => 'change me',
            'KeyId' => 'change me',
            'Overwrite' => false,
            'AllowedPattern' => 'change me',
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
            'Tier' => 'change me',
            'Policies' => 'change me',
        ]);
        $result = $client->PutParameter($input);

        $result->resolve();

        self::assertSame(1337, $result->getVersion());
        self::assertSame('changeIt', $result->getTier());
    }

    private function getClient(): SsmClient
    {
        self::markTestSkipped('No Docker image for SSM');

        return new SsmClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
