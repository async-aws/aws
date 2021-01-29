<?php

namespace AsyncAws\Ssm\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Enum\ParameterTier;
use AsyncAws\Ssm\Enum\ParameterType;
use AsyncAws\Ssm\Input\DeleteParameterRequest;
use AsyncAws\Ssm\Input\GetParameterRequest;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\Input\GetParametersRequest;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\Tag;

class SsmClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->getClient()->putParameter(['Name' => '/app/database/host', 'Value' => 'localhost', 'Overwrite' => true]);
        while (true) {
            try {
                $this->getClient()->getParameter(['Name' => '/app/database/host']);

                break;
            } catch (ClientException $e) {
                if ('ParameterNotFound' !== $e->getAwsCode()) {
                    throw $e;
                }
                \usleep(10000);
            }
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();

        try {
            $this->getClient()->deleteParameter(['Name' => '/app/database/host']);
            while (true) {
                try {
                    $this->getClient()->getParameter(['Name' => '/app/database/host']);
                    \usleep(10000);
                } catch (ClientException $e) {
                    if ('ParameterNotFound' !== $e->getAwsCode()) {
                        throw $e;
                    }

                    break;
                }
            }
        } catch (ClientException $e) {
            if ('ParameterNotFound' !== $e->getAwsCode()) {
                throw $e;
            }
        }
    }

    public function testDeleteParameter(): void
    {
        $client = $this->getClient();

        $input = new DeleteParameterRequest([
            'Name' => '/app/database/host',
        ]);
        $result = $client->deleteParameter($input);

        self::expectNotToPerformAssertions();
        $result->resolve();
    }

    public function testGetParameter(): void
    {
        $client = $this->getClient();

        $input = new GetParameterRequest([
            'Name' => '/app/database/host',
            'WithDecryption' => false,
        ]);
        $result = $client->getParameter($input);

        self::assertNotNull($result->getParameter());
        self::assertSame('localhost', $result->getParameter()->getValue());
    }

    public function testGetParameters(): void
    {
        $client = $this->getClient();

        $input = new GetParametersRequest([
            'Names' => ['/app/database/host'],
            'WithDecryption' => false,
        ]);
        $result = $client->GetParameters($input);

        self::assertCount(1, $result->getParameters());
        self::assertCount(0, $result->getInvalidParameters());
        self::assertSame('/app/database/host', $result->getParameters()[0]->getName());
    }

    public function testGetParametersByPath(): void
    {
        $client = $this->getClient();

        $input = new GetParametersByPathRequest([
            'Path' => '/app/database',
            'Recursive' => true,
        ]);
        $result = $client->GetParametersByPath($input);
        $parameters = \iterator_to_array($result->getParameters());
        self::assertCount(1, $parameters);
        self::assertSame('/app/database/host', $parameters[0]->getName());
    }

    public function testPutParameter(): void
    {
        $client = $this->getClient();

        try {
            $this->getClient()->deleteParameter(['Name' => '/app/smtp/user']);
            while (true) {
                try {
                    $this->getClient()->getParameter(['Name' => '/app/smtp/user']);
                    \usleep(10000);
                } catch (ClientException $e) {
                    if ('ParameterNotFound' !== $e->getAwsCode()) {
                        throw $e;
                    }

                    break;
                }
            }
        } catch (ClientException $e) {
            if ('ParameterNotFound' !== $e->getAwsCode()) {
                throw $e;
            }
        }

        $input = new PutParameterRequest([
            'Name' => '/app/smtp/user',
            'Description' => 'The username of SMTP',
            'Value' => 'root',
            'Type' => ParameterType::STRING,
            'Overwrite' => true,
            'Tags' => [new Tag([
                'Key' => 'group',
                'Value' => 'demo',
            ])],
            'Tier' => ParameterTier::STANDARD,
        ]);
        $result = $client->PutParameter($input);

        self::assertSame('1', $result->getVersion());

        $input = new PutParameterRequest([
            'Name' => '/app/smtp/user',
            'Value' => 'admin',
            'Overwrite' => true,
        ]);
        $result = $client->PutParameter($input);
        $result->resolve();
        self::assertSame('2', $result->getVersion());
        $parameter = $client->getParameter(['Name' => '/app/smtp/user'])->getParameter();
        self::assertSame('admin', $parameter->getValue());
    }

    private function getClient(): SsmClient
    {
        return new SsmClient([
            'endpoint' => 'http://localhost:4574',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
