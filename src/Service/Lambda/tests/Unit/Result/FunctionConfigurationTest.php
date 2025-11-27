<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\FunctionConfiguration;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class FunctionConfigurationTest extends TestCase
{
    public function testFunctionConfiguration(): void
    {
        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_UpdateFunctionConfiguration.html
        $response = new SimpleMockedResponse('{
            "FunctionName": "test-func",
            "FunctionArn": "arn:test-fun",
            "MasterArn": "arn:master-test-func",
            "CodeSize": 1233,
            "LastModified": "2023-08-22T18:01:12+0000",
            "CodeSha256": "hash",
            "Version": "90",
            "State": "Active",
            "DeadLetterConfig": {
                "TargetArn": "testDeadLetterConfig"
            },
            "Description": "testDescription",
            "Environment": {
                "Variables": {
                    "testEnvKey": "testEnvValue"
                }
            },
            "EphemeralStorage": {
                "Size": 1337
            },
            "FileSystemConfigs": [
                {
                    "Arn": "testFileArn",
                    "LocalMountPath": "testMountPoint"
                }
            ],
            "Handler": "testHandler",
            "ImageConfig": {
                "Command": [
                    "testImageConfigCommand"
                ],
                "EntryPoint": [
                    "testImageEntryPoint"
                ],
                "WorkingDirectory": "testImageConfigWorkingDirectory"
            },
            "KMSKeyArn": "testKmsKeyArn",
            "Layers": [
                {"Arn":"testLayer"}
            ],
            "MemorySize": 1337,
            "RevisionId": "testRevisionId",
            "Role": "testRole",
            "Runtime": "nodejs14.x",
            "SnapStart": {
                "ApplyOn": "None"
            },
            "Timeout": 1337,
            "TracingConfig": {
                "Mode": "PassThrough"
            },
            "VpcConfig": {
                "SecurityGroupIds": [
                    "testSecurityGroupIds"
                ],
                "SubnetIds": [
                    "testSubnetId"
                ]
            },
            "LastUpdateStatus": "Successful",
            "LastUpdateStatusReason": "test",
            "LastUpdateStatusReasonCode": null,
            "PackageType": "Zip",
            "Architectures": ["x86_64"]
        }');

        $client = new MockHttpClient($response);
        $result = new FunctionConfiguration(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('test-func', $result->getFunctionName());
        self::assertSame('arn:test-fun', $result->getFunctionArn());
        self::assertSame('nodejs14.x', $result->getRuntime());
        self::assertSame('testRole', $result->getRole());
        self::assertSame('testHandler', $result->getHandler());
        self::assertSame(1233, $result->getCodeSize());
        self::assertSame('testDescription', $result->getDescription());
        self::assertSame(1337, $result->getTimeout());
        self::assertSame(1337, $result->getMemorySize());
        self::assertSame('2023-08-22T18:01:12+0000', $result->getLastModified());
        self::assertSame('hash', $result->getCodeSha256());
        self::assertSame('90', $result->getVersion());
        self::assertSame(['testSubnetId'], $result->getVpcConfig()->getSubnetIds());
        self::assertSame(['testSecurityGroupIds'], $result->getVpcConfig()->getSecurityGroupIds());
        self::assertSame(null, $result->getVpcConfig()->getVpcId());
        self::assertSame('testDeadLetterConfig', $result->getDeadLetterConfig()->getTargetArn());
        self::assertSame([
            'testEnvKey' => 'testEnvValue',
        ], $result->getEnvironment()->getVariables());
        self::assertSame('testKmsKeyArn', $result->getKmsKeyArn());
        self::assertSame('PassThrough', $result->getTracingConfig()->getMode());
        self::assertSame('arn:master-test-func', $result->getMasterArn());
        self::assertSame('testRevisionId', $result->getRevisionId());
        self::assertSame('Active', $result->getState());
        self::assertSame(null, $result->getStateReason());
        self::assertSame(null, $result->getStateReasonCode());
        self::assertSame('Successful', $result->getLastUpdateStatus());
        self::assertSame('test', $result->getLastUpdateStatusReason());
        self::assertSame(null, $result->getLastUpdateStatusReasonCode());
        self::assertSame('Zip', $result->getPackageType());
        self::assertSame(null, $result->getSigningProfileVersionArn());
        self::assertSame(null, $result->getSigningJobArn());
        self::assertCount(1, $result->getLayers());
        self::assertSame('testLayer', $result->getLayers()[0]->getArn());
        self::assertCount(1, $result->getFileSystemConfigs());
        self::assertSame([
            'Arn' => 'testFileArn',
            'LocalMountPath' => 'testMountPoint',
        ], $result->getFileSystemConfigs()[0]->requestBody());
        self::assertNull($result->getImageConfigResponse());
        self::assertCount(1, $result->getArchitectures());
        self::assertSame('x86_64', $result->getArchitectures()[0]);
        self::assertSame(1337, $result->getEphemeralStorage()->getSize());
        self::assertSame('None', $result->getSnapStart()->getApplyOn());
        self::assertSame(null, $result->getSnapStart()->getOptimizationStatus());
        self::assertSame(null, $result->getRuntimeVersionConfig());
    }
}
