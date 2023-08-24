<?php

namespace AsyncAws\Lambda\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Enum\SnapStartApplyOn;
use AsyncAws\Lambda\Enum\TracingMode;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\DeleteFunctionRequest;
use AsyncAws\Lambda\Input\GetFunctionConfigurationRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\ListFunctionsRequest;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\Input\UpdateFunctionConfigurationRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Result\InvocationResponse;
use AsyncAws\Lambda\ValueObject\DeadLetterConfig;
use AsyncAws\Lambda\ValueObject\Environment;
use AsyncAws\Lambda\ValueObject\EphemeralStorage;
use AsyncAws\Lambda\ValueObject\FileSystemConfig;
use AsyncAws\Lambda\ValueObject\ImageConfig;
use AsyncAws\Lambda\ValueObject\LayerVersionContentInput;
use AsyncAws\Lambda\ValueObject\SnapStart;
use AsyncAws\Lambda\ValueObject\TracingConfig;
use AsyncAws\Lambda\ValueObject\VpcConfig;

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

    public function testDeleteFunction(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement DeleteFunction.');

        $client = $this->getClient();

        $input = new DeleteFunctionRequest([
            'FunctionName' => 'Index',
        ]);
        $result = $client->DeleteFunction($input);

        $result->resolve();
    }

    public function testGetFunctionConfiguration(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement GetFunctionConfiguration.');
        $client = $this->getClient();

        $input = new GetFunctionConfigurationRequest([
            'FunctionName' => 'change me',
            'Qualifier' => 'change me',
        ]);
        $result = $client->getFunctionConfiguration($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getFunctionName());
        self::assertSame('changeIt', $result->getFunctionArn());
        self::assertSame('changeIt', $result->getRuntime());
        self::assertSame('changeIt', $result->getRole());
        self::assertSame('changeIt', $result->getHandler());
        self::assertSame(1337, $result->getCodeSize());
        self::assertSame('changeIt', $result->getDescription());
        self::assertSame(1337, $result->getTimeout());
        self::assertSame(1337, $result->getMemorySize());
        self::assertSame('changeIt', $result->getLastModified());
        self::assertSame('changeIt', $result->getCodeSha256());
        self::assertSame('changeIt', $result->getVersion());
        // self::assertTODO(expected, $result->getVpcConfig());
        // self::assertTODO(expected, $result->getDeadLetterConfig());
        // self::assertTODO(expected, $result->getEnvironment());
        self::assertSame('changeIt', $result->getKmsKeyArn());
        // self::assertTODO(expected, $result->getTracingConfig());
        self::assertSame('changeIt', $result->getMasterArn());
        self::assertSame('changeIt', $result->getRevisionId());
        // self::assertTODO(expected, $result->getLayers());
        self::assertSame('changeIt', $result->getState());
        self::assertSame('changeIt', $result->getStateReason());
        self::assertSame('changeIt', $result->getStateReasonCode());
        self::assertSame('changeIt', $result->getLastUpdateStatus());
        self::assertSame('changeIt', $result->getLastUpdateStatusReason());
        self::assertSame('changeIt', $result->getLastUpdateStatusReasonCode());
        // self::assertTODO(expected, $result->getFileSystemConfigs());
        self::assertSame('changeIt', $result->getPackageType());
        // self::assertTODO(expected, $result->getImageConfigResponse());
        self::assertSame('changeIt', $result->getSigningProfileVersionArn());
        self::assertSame('changeIt', $result->getSigningJobArn());
        // self::assertTODO(expected, $result->getArchitectures());
        // self::assertTODO(expected, $result->getEphemeralStorage());
        // self::assertTODO(expected, $result->getSnapStart());
        // self::assertTODO(expected, $result->getRuntimeVersionConfig());
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

    public function testInvokeWait(): void
    {
        if (!method_exists(Result::class, 'wait')) {
            self::markTestSkipped('Core does not contains the feature');
        }

        $client = $this->getClient();

        $results = [];
        $expected = [];
        for ($i = 0; $i < 27; ++$i) {
            $expected[] = '"hello ' . $i . '"';
            $results[] = $client->Invoke(new InvocationRequest([
                'FunctionName' => 'Index',
                'Payload' => json_encode(['name' => $i, 'delay' => 80 * ($i % 3)]),
            ]));
        }

        $resolves = [];
        /** @var InvocationResponse[] $result */
        foreach (Result::wait($results, null, true) as $index => $result) {
            // assert $index match original order
            self::assertSame($expected[$index], $result->getPayload());
            $resolves[] = $result->getPayload();
        }

        self::assertEqualsCanonicalizing($expected, $resolves);
    }

    public function testListFunctions(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement ListFunctions.');

        $client = $this->getClient();

        $input = new ListFunctionsRequest([
            'MaxItems' => 1337,
        ]);
        $result = $client->ListFunctions($input);

        $result->resolve();

        self::assertSame('Index', $result->getNextMarker());
        // self::assertTODO(expected, $result->getFunctions());
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

    public function testListVersionsByFunction(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement ListVersionsByFunction.');

        $client = $this->getClient();

        $input = new ListVersionsByFunctionRequest([
            'FunctionName' => 'Index',
        ]);
        $result = $client->ListVersionsByFunction($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getNextMarker());
        // self::assertTODO(expected, $result->getVersions());
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

    public function testUpdateFunctionConfiguration(): void
    {
        self::markTestSkipped('The Lambda Docker image does not implement UpdateFunctionConfiguration.');

        $client = $this->getClient();

        $input = new UpdateFunctionConfigurationRequest([
            'FunctionName' => 'test-func',
            'Role' => 'testRole',
            'Handler' => 'testHandler',
            'Description' => 'testDescription',
            'Timeout' => 1337,
            'MemorySize' => 1337,
            'VpcConfig' => new VpcConfig([
                'SubnetIds' => ['testSubnetId'],
                'SecurityGroupIds' => ['testSecurityGroupIds'],
            ]),
            'Environment' => new Environment([
                'Variables' => ['testEnvKey' => 'testEnvValue'],
            ]),
            'Runtime' => Runtime::NODEJS_14_X,
            'DeadLetterConfig' => new DeadLetterConfig([
                'TargetArn' => 'testDeadLetterConfig',
            ]),
            'KMSKeyArn' => 'testKmsKeyArn',
            'TracingConfig' => new TracingConfig([
                'Mode' => TracingMode::PASS_THROUGH,
            ]),
            'RevisionId' => 'testRevisionId',
            'Layers' => ['testLayer'],
            'FileSystemConfigs' => [new FileSystemConfig([
                'Arn' => 'testFileArn',
                'LocalMountPath' => 'testMountPoint',
            ])],
            'ImageConfig' => new ImageConfig([
                'EntryPoint' => ['testImageEntryPoint'],
                'Command' => ['testImageConfigCommand'],
                'WorkingDirectory' => 'testImageConfigWorkingDirectory',
            ]),
            'EphemeralStorage' => new EphemeralStorage([
                'Size' => 1337,
            ]),
            'SnapStart' => new SnapStart([
                'ApplyOn' => SnapStartApplyOn::NONE,
            ]),
        ]);
        $result = $client->updateFunctionConfiguration($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getFunctionName());
        self::assertSame('changeIt', $result->getFunctionArn());
        self::assertSame('changeIt', $result->getRuntime());
        self::assertSame('changeIt', $result->getRole());
        self::assertSame('changeIt', $result->getHandler());
        self::assertSame(1337, $result->getCodeSize());
        self::assertSame('changeIt', $result->getDescription());
        self::assertSame(1337, $result->getTimeout());
        self::assertSame(1337, $result->getMemorySize());
        self::assertSame('changeIt', $result->getLastModified());
        self::assertSame('changeIt', $result->getCodeSha256());
        self::assertSame('changeIt', $result->getVersion());
        // self::assertTODO(expected, $result->getVpcConfig());
        // self::assertTODO(expected, $result->getDeadLetterConfig());
        // self::assertTODO(expected, $result->getEnvironment());
        self::assertSame('changeIt', $result->getKmsKeyArn());
        // self::assertTODO(expected, $result->getTracingConfig());
        self::assertSame('changeIt', $result->getMasterArn());
        self::assertSame('changeIt', $result->getRevisionId());
        // self::assertTODO(expected, $result->getLayers());
        self::assertSame('changeIt', $result->getState());
        self::assertSame('changeIt', $result->getStateReason());
        self::assertSame('changeIt', $result->getStateReasonCode());
        self::assertSame('changeIt', $result->getLastUpdateStatus());
        self::assertSame('changeIt', $result->getLastUpdateStatusReason());
        self::assertSame('changeIt', $result->getLastUpdateStatusReasonCode());
        // self::assertTODO(expected, $result->getFileSystemConfigs());
        self::assertSame('changeIt', $result->getPackageType());
        // self::assertTODO(expected, $result->getImageConfigResponse());
        self::assertSame('changeIt', $result->getSigningProfileVersionArn());
        self::assertSame('changeIt', $result->getSigningJobArn());
        // self::assertTODO(expected, $result->getArchitectures());
        // self::assertTODO(expected, $result->getEphemeralStorage());
        // self::assertTODO(expected, $result->getSnapStart());
        // self::assertTODO(expected, $result->getRuntimeVersionConfig());
    }

    private function getClient(): LambdaClient
    {
        return new LambdaClient([
            'endpoint' => 'http://localhost:9001',
        ], new NullProvider());
    }
}
