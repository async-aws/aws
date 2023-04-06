<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetCalculationExecutionResponse;
use AsyncAws\Athena\ValueObject\CalculationResult;
use AsyncAws\Athena\ValueObject\CalculationStatistics;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetCalculationExecutionResponseTest extends TestCase
{
    public function testGetCalculationExecutionResponse(): void
    {
        $CalculationExecutionId = '1cd6412f-92bf-4e7d-a044-c1d010b94007';

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetCalculationExecution.html
        $response = new SimpleMockedResponse('{
            "CalculationExecutionId": "1cd6412f-92bf-4e7d-a044-c1d010b94007",
            "Description": "iad international async_aws",
            "Result": {
               "ResultS3Uri": "s3://iad_bucket",
               "ResultType": "json",
               "StdErrorS3Uri": "s3://iad_bucket/stderr",
               "StdOutS3Uri": "s3://iad_bucket/stdout"
            },
            "SessionId": "session-iad-1526",
            "Statistics": {
               "DpuExecutionInMillis": 1337,
               "Progress": "in progress"
            },
            "Status": {
               "CompletionDateTime": 1337,
               "State": "RUNNING",
               "StateChangeReason": "new query",
               "SubmissionDateTime": 1337
            },
            "WorkingDirectory": "iad-directory"
        }');

        $client = new MockHttpClient($response);
        $result = new GetCalculationExecutionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame($CalculationExecutionId, $result->getCalculationExecutionId());
        self::assertSame('session-iad-1526', $result->getSessionId());
        self::assertSame('iad international async_aws', $result->getDescription());
        self::assertSame('iad-directory', $result->getWorkingDirectory());
        self::assertSame('RUNNING', $result->getStatus()->getState());
        self::assertInstanceOf(CalculationStatistics::class, $result->getStatistics());
        self::assertSame('1337', $result->getStatistics()->getDpuExecutionInMillis());
        self::assertInstanceOf(CalculationResult::class, $result->getResult());
    }
}
