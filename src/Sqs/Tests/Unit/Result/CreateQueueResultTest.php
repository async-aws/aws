<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\CreateQueueResult;
use PHPUnit\Framework\TestCase;

class CreateQueueResultTest extends TestCase
{
    public function testCreateQueueResult()
    {
        $response = new SimpleMockedResponse(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<CreateQueueResponse>
    <CreateQueueResult>
        <QueueUrl>https://queue.amazonaws.com/123456789012/MyQueue</QueueUrl>
    </CreateQueueResult>
    <ResponseMetadata>
        <RequestId>7a62c49f-347e-4fc4-9331-6e8e7a96aa73</RequestId>
    </ResponseMetadata>
</CreateQueueResponse>
XML
        );

        $result = new CreateQueueResult($response);

        self::assertEquals('https://queue.amazonaws.com/123456789012/MyQueue', $result->getQueueUrl());
    }
}
