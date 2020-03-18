<?php

namespace AsyncAws\Lambda\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\ValueObject\LayerVersionContentInput;
use PHPUnit\Framework\TestCase;

class LambdaClientTest extends TestCase
{
    public function testAddLayerVersionPermission(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement AddLayerVersionPermission.');

        $client = $this->getClient();

        $input = new AddLayerVersionPermissionRequest([
            'LayerName' => 'change me',
            'VersionNumber' => 1337,
            'StatementId' => 'change me',
            'Action' => 'change me',
            'Principal' => 'change me',
            'OrganizationId' => 'change me',
            'RevisionId' => 'change me',
        ]);
        $result = $client->AddLayerVersionPermission($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getStatement());
        self::assertStringContainsString('change it', $result->getRevisionId());
    }

    public function testInvoke(): void
    {
        $client = $this->getClient();

        $input = new InvocationRequest([
            'FunctionName' => 'Index',
            'Payload' => '{"name": "jderusse"}',
        ]);
        $result = $client->Invoke($input);

        $result->resolve();

        self::assertSame(200, $result->getStatusCode());
        self::assertNull($result->getFunctionError());
        self::assertNull($result->getLogResult());
        self::assertSame('"hello jderusse"', $result->getPayload());
        self::assertSame('$LATEST', $result->getExecutedVersion());
    }

    public function testListLayerVersions(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement AddLayerVersionPermission.');

        $client = $this->getClient();

        $input = new ListLayerVersionsRequest([
            'CompatibleRuntime' => 'nodejs12.x',
            'LayerName' => 'foo',
        ]);
        $result = $client->ListLayerVersions($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getNextMarker());
        // self::assertTODO(expected, $result->getLayerVersions());
    }

    public function testPublishLayerVersion(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement AddLayerVersionPermission.');

        $client = $this->getClient();

        $input = new PublishLayerVersionRequest([
            'LayerName' => 'foo',
            'Description' => 'change me',
            'Content' => new LayerVersionContentInput([
                'S3Bucket' => 'change me',
                'S3Key' => 'change me',
                'S3ObjectVersion' => 'change me',
                'ZipFile' => 'change me',
            ]),
            'CompatibleRuntimes' => ['nodejs12.x'],
            'LicenseInfo' => 'change me',
        ]);
        $result = $client->PublishLayerVersion($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getContent());
        self::assertStringContainsString('change it', $result->getLayerArn());
        self::assertStringContainsString('change it', $result->getLayerVersionArn());
        self::assertStringContainsString('change it', $result->getDescription());
        self::assertStringContainsString('change it', $result->getCreatedDate());
        self::assertSame(1337, $result->getVersion());
        // self::assertTODO(expected, $result->getCompatibleRuntimes());
        self::assertStringContainsString('change it', $result->getLicenseInfo());
    }

    private function getClient(): LambdaClient
    {
        return new LambdaClient([
            'endpoint' => 'http://localhost:9001',
        ], new NullProvider());
    }
}
