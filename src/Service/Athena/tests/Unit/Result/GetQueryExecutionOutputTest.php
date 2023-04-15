<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetQueryExecutionOutput;
use AsyncAws\Athena\ValueObject\QueryExecution;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetQueryExecutionOutputTest extends TestCase
{
    public function testGetQueryExecutionOutput(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetQueryExecution.html
        $response = new SimpleMockedResponse('{
           "QueryExecution": {
              "EngineVersion": {
                 "EffectiveEngineVersion": "AUTO",
                 "SelectedEngineVersion": "AUTO"
              },
              "ExecutionParameters": [],
              "Query": "INSERT INTO iadDatabase.elb_backup SELECT * FROM iadDatabase.elb LIMIT 100",
              "QueryExecutionContext": {
                 "Catalog": "iadCatalog",
                 "Database": "iadDatabase"
              },
              "QueryExecutionId": "f2dbee00-d546-11ed-bd1f",
              "ResultConfiguration": {
                 "AclConfiguration": {
                    "S3AclOption": "BUCKET_OWNER_FULL_CONTROL"
                 },
                 "EncryptionConfiguration": {
                    "EncryptionOption": "SSE_S3",
                    "KmsKey": "myKey"
                 },
                 "ExpectedBucketOwner": "iad",
                 "OutputLocation": "s3://oupout_bucket"
              },
              "ResultReuseConfiguration": {
                 "ResultReuseByAgeConfiguration": {
                    "Enabled": false,
                    "MaxAgeInMinutes": 1998
                 }
              },
              "StatementType": "DML",
              "Statistics": {
                 "DataManifestLocation": "s3://my_bucket-us-west-1/path/34-manifest.csv",
                 "DataScannedInBytes": 200,
                 "EngineExecutionTimeInMillis": 200,
                 "QueryPlanningTimeInMillis": 200,
                 "QueryQueueTimeInMillis": 500,
                 "ResultReuseInformation": {
                    "ReusedPreviousResult": false
                 },
                 "ServiceProcessingTimeInMillis": 1000,
                 "TotalExecutionTimeInMillis": 1000
              },
              "Status": {
                 "AthenaError": {
                    "ErrorCategory": 1998,
                    "ErrorMessage": "string",
                    "ErrorType": 1998,
                    "Retryable": true
                 },
                 "CompletionDateTime": 1680882467.121,
                 "State": "RUNNING",
                 "StateChangeReason": "65 lignes treated",
                 "SubmissionDateTime": 1680882467.123
              },
              "SubstatementType": "INSERTION",
              "WorkGroup": "iadinternational"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new GetQueryExecutionOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(QueryExecution::class, $result->getQueryExecution());
        self::assertSame('INSERTION', $result->getQueryExecution()->getSubstatementType());
        self::assertSame('DML', $result->getQueryExecution()->getStatementType());
    }
}
