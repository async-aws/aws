<?php

namespace AsyncAws\Ec2\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ec2\Ec2Client;
use AsyncAws\Ec2\Input\DeleteSnapshotRequest;
use AsyncAws\Ec2\Input\DeregisterImageRequest;
use AsyncAws\Ec2\Input\DescribeImagesRequest;
use AsyncAws\Ec2\Result\DeregisterImageResult;
use AsyncAws\Ec2\Result\DescribeImagesResult;
use Symfony\Component\HttpClient\MockHttpClient;

class Ec2ClientTest extends TestCase
{
    public function testDeleteSnapshot(): void
    {
        $client = new Ec2Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteSnapshotRequest([
            'SnapshotId' => 'change me',
        ]);
        $result = $client->deleteSnapshot($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeregisterImage(): void
    {
        $client = new Ec2Client([], new NullProvider(), new MockHttpClient());

        $input = new DeregisterImageRequest([
            'ImageId' => 'change me',
        ]);
        $result = $client->deregisterImage($input);

        self::assertInstanceOf(DeregisterImageResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDescribeImages(): void
    {
        $client = new Ec2Client([], new NullProvider(), new MockHttpClient());

        $input = new DescribeImagesRequest([
        ]);
        $result = $client->describeImages($input);

        self::assertInstanceOf(DescribeImagesResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
