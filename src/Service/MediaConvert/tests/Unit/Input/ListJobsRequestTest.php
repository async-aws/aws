<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Enum\JobStatus;
use AsyncAws\MediaConvert\Enum\Order;
use AsyncAws\MediaConvert\Input\ListJobsRequest;

class ListJobsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListJobsRequest([
            'MaxResults' => 1337,
            'NextToken' => 'fakeToken',
            'Order' => Order::ASCENDING,
            'Queue' => 'fakeQueue',
            'Status' => JobStatus::COMPLETE,
        ]);

        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_ListJobs.html
        $expected = '
            GET /2017-08-29/jobs?maxResults=1337&nextToken=fakeToken&order=ASCENDING&queue=fakeQueue&status=COMPLETE HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
