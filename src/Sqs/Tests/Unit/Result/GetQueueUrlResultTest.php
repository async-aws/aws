<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\GetQueueUrlResult;
use PHPUnit\Framework\TestCase;

class GetQueueUrlResultTest extends TestCase
{
    public function testGetQueueUrlResult()
    {
        $response = new SimpleMockedResponse(<<<XML
<GetQueueUrlResponse>
    <GetQueueUrlResult>
        <QueueUrl>https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue</QueueUrl>
    </GetQueueUrlResult>
    <ResponseMetadata>
        <RequestId>470a6f13-2ed9-4181-ad8a-2fdea142988e</RequestId>
    </ResponseMetadata>
</GetQueueUrlResponse>
XML
        );

        $result = new GetQueueUrlResult($response);

        self::assertEquals('https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue', $result->getQueueUrl());
    }
}
