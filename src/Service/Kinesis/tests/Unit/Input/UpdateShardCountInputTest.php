<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\ScalingType;
use AsyncAws\Kinesis\Input\UpdateShardCountInput;

class UpdateShardCountInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateShardCountInput([
            'StreamName' => 'exampleStreamName',
            'TargetShardCount' => 4,
            'ScalingType' => ScalingType::UNIFORM_SCALING,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_UpdateShardCount.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.UpdateShardCount
Accept: application/json

{
  "StreamName": "exampleStreamName",
  "TargetShardCount": 4,
  "ScalingType": "UNIFORM_SCALING"
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
