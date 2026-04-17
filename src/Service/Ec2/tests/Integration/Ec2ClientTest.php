<?php

namespace AsyncAws\Ec2\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ec2\Ec2Client;
use AsyncAws\Ec2\Input\DeleteSnapshotRequest;
use AsyncAws\Ec2\Input\DeregisterImageRequest;
use AsyncAws\Ec2\Input\DescribeImagesRequest;
use AsyncAws\Ec2\ValueObject\Filter;

class Ec2ClientTest extends TestCase
{
    public function testDeleteSnapshot(): void
    {
        $client = $this->getClient();

        $input = new DeleteSnapshotRequest([
            'SnapshotId' => 'change me',
            'DryRun' => false,
        ]);
        $result = $client->deleteSnapshot($input);

        $result->resolve();
    }

    public function testDeregisterImage(): void
    {
        $client = $this->getClient();

        $input = new DeregisterImageRequest([
            'ImageId' => 'change me',
            'DeleteAssociatedSnapshots' => false,
            'DryRun' => false,
        ]);
        $result = $client->deregisterImage($input);

        $result->resolve();

        self::assertFalse($result->getReturn());
        // self::assertTODO(expected, $result->getDeleteSnapshotResults());
    }

    public function testDescribeImages(): void
    {
        $client = $this->getClient();

        $input = new DescribeImagesRequest([
            'ExecutableUsers' => ['change me'],
            'ImageIds' => ['change me'],
            'Owners' => ['change me'],
            'IncludeDeprecated' => false,
            'IncludeDisabled' => false,
            'MaxResults' => 1337,
            'NextToken' => 'change me',
            'DryRun' => false,
            'Filters' => [new Filter([
                'Name' => 'change me',
                'Values' => ['change me'],
            ])],
        ]);
        $result = $client->describeImages($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getImages());
    }

    private function getClient(): Ec2Client
    {
        self::markTestSkipped('There is no docker image available for EC2.');

        return new Ec2Client([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
