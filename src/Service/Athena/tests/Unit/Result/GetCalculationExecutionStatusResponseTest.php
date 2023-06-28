<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetCalculationExecutionStatusResponse;
use AsyncAws\Athena\ValueObject\CalculationStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetCalculationExecutionStatusResponseTest extends TestCase
{
    public function testGetCalculationExecutionStatusResponse(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetCalculationExecutionStatus.html
        $response = new SimpleMockedResponse('{
           "Statistics": {
              "DpuExecutionInMillis": 1994,
              "Progress": "90 percent loading"
           },
           "Status": {
              "CompletionDateTime": 1994,
              "State": "CREATED",
              "StateChangeReason": "iad kpi",
              "SubmissionDateTime": 1994
           }
}');

        $client = new MockHttpClient($response);
        $result = new GetCalculationExecutionStatusResponse(
            new Response($client->request('POST', 'http://localhost'), $client, new NullLogger())
        );

        self::assertInstanceOf(CalculationStatus::class, $result->getStatus());
        self::assertSame('CREATED', $result->getStatus()->getState());
        self::assertIsInt($result->getStatistics()->getDpuExecutionInMillis());
    }
}
