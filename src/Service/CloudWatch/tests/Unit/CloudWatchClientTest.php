<?php

namespace AsyncAws\CloudWatch\Tests\Unit;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\PutMetricAlarmInput;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CloudWatchClientTest extends TestCase
{
    public function testPutMetricAlarm(): void
    {
        $client = new CloudWatchClient([], new NullProvider(), new MockHttpClient());

        $input = new PutMetricAlarmInput([
            'AlarmName' => 'changeMe',

            'EvaluationPeriods' => 1337,

            'ComparisonOperator' => 'changeMe',

        ]);
        $result = $client->PutMetricAlarm($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
