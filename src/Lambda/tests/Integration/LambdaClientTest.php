<?php

namespace AsyncAws\Lambda\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\LayerVersionContentInput;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\LambdaClient;
use PHPUnit\Framework\TestCase;

class LambdaClientTest extends TestCase
{
    public function testAddLayerVersionPermission(): void
    {
        self::markTestIncomplete('Not implemented');

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
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new InvocationRequest([
            'FunctionName' => 'change me',
            'InvocationType' => 'change me',
            'LogType' => 'change me',
            'ClientContext' => 'change me',
            'Payload' => 'change me',
            'Qualifier' => 'change me',
        ]);
        $result = $client->Invoke($input);

        $result->resolve();

        self::assertSame(1337, $result->getStatusCode());
        self::assertStringContainsString('change it', $result->getFunctionError());
        self::assertStringContainsString('change it', $result->getLogResult());
        // self::assertTODO(expected, $result->getPayload());
        self::assertStringContainsString('change it', $result->getExecutedVersion());
    }

    public function testListLayerVersions(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new ListLayerVersionsRequest([
            'CompatibleRuntime' => 'change me',
            'LayerName' => 'change me',
            'Marker' => 'change me',
            'MaxItems' => 1337,
        ]);
        $result = $client->ListLayerVersions($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getNextMarker());
        // self::assertTODO(expected, $result->getLayerVersions());
    }

    public function testPublishLayerVersion(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new PublishLayerVersionRequest([
            'LayerName' => 'change me',
            'Description' => 'change me',
            'Content' => new LayerVersionContentInput([
                'S3Bucket' => 'change me',
                'S3Key' => 'change me',
                'S3ObjectVersion' => 'change me',
                'ZipFile' => 'change me',
            ]),
            'CompatibleRuntimes' => ['change me'],
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
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
