<?php

namespace AsyncAws\Ec2\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ec2\Input\DeleteSnapshotRequest;

class DeleteSnapshotRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteSnapshotRequest([
            'SnapshotId' => 'snap-0aaaaaaaaaaaaaaa1',
        ]);

        // see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DeleteSnapshot.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DeleteSnapshot
            &Version=2016-11-15
            &SnapshotId=snap-0aaaaaaaaaaaaaaa1
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
