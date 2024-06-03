<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Enum\SnapStartApplyOn;
use AsyncAws\Lambda\Enum\TracingMode;
use AsyncAws\Lambda\Input\UpdateFunctionConfigurationRequest;
use AsyncAws\Lambda\ValueObject\DeadLetterConfig;
use AsyncAws\Lambda\ValueObject\Environment;
use AsyncAws\Lambda\ValueObject\EphemeralStorage;
use AsyncAws\Lambda\ValueObject\FileSystemConfig;
use AsyncAws\Lambda\ValueObject\ImageConfig;
use AsyncAws\Lambda\ValueObject\SnapStart;
use AsyncAws\Lambda\ValueObject\TracingConfig;
use AsyncAws\Lambda\ValueObject\VpcConfig;

class UpdateFunctionConfigurationRequestTest extends TestCase
{
    public function testRequest(): void
    {
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

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_UpdateFunctionConfiguration.html
        $expected = '
            PUT /2015-03-31/functions/test-func/configuration HTTP/1.0
            Content-type: application/json
            Accept: application/json

            {
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
                    "testLayer"
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
                }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
