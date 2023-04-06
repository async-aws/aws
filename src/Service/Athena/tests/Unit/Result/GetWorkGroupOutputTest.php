<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetWorkGroupOutput;
use AsyncAws\Athena\ValueObject\WorkGroup;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetWorkGroupOutputTest extends TestCase
{
    public function testGetWorkGroupOutput(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetWorkGroup.html
        $response = new SimpleMockedResponse('{
           "WorkGroup": {
              "Configuration": {
                 "AdditionalConfiguration": "iad contrib",
                 "BytesScannedCutoffPerQuery": 1000,
                 "CustomerContentEncryptionConfiguration": {
                    "KmsKey": "iadKey"
                 },
                 "EnableMinimumEncryptionConfiguration": true,
                 "EnforceWorkGroupConfiguration": false,
                 "EngineVersion": {
                    "EffectiveEngineVersion": "AUTO",
                    "SelectedEngineVersion": "AUTO"
                 },
                 "ExecutionRole": "arn:aws:iam::sandbox-iad:role/test",
                 "PublishCloudWatchMetricsEnabled": true,
                 "RequesterPaysEnabled": false,
                 "ResultConfiguration": {
                    "AclConfiguration": {
                       "S3AclOption": " BUCKET_OWNER_FULL_CONTROL"
                    },
                    "EncryptionConfiguration": {
                       "EncryptionOption": "SSE_S3",
                       "KmsKey": "iadKey"
                    },
                    "ExpectedBucketOwner": "iadOwner",
                    "OutputLocation": "s3://iad_bucket/ouput/"
                 }
              },
              "CreationTime": 1680773161,
              "Description": "iad team workgroup",
              "Name": "iadWorkgroup",
              "State": "ENABLED"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new GetWorkGroupOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(WorkGroup::class, $result->getWorkGroup());
        self::assertSame('ENABLED', $result->getWorkGroup()->getState());
        self::assertSame('iadWorkgroup', $result->getWorkGroup()->getName());
    }
}
