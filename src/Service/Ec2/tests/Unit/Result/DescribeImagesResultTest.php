<?php

namespace AsyncAws\Ec2\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ec2\Ec2Client;
use AsyncAws\Ec2\Input\DescribeImagesRequest;
use AsyncAws\Ec2\Result\DescribeImagesResult;
use AsyncAws\Ec2\ValueObject\Image;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeImagesResultTest extends TestCase
{
    public function testDescribeImagesResult(): void
    {
        // Response element names come from EC2 model locationNames:
        //   imagesSet / <item> / imageId / creationDate / blockDeviceMapping / ebs / snapshotId
        // see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeImages.html
        $response = new SimpleMockedResponse('<DescribeImagesResponse xmlns="http://ec2.amazonaws.com/doc/2016-11-15/">
    <requestId>3be1508e-c444-4fef-89cc-0b1223c4f02fEXAMPLE</requestId>
    <imagesSet>
        <item>
            <imageId>ami-abc12345</imageId>
            <creationDate>2024-06-01T12:34:56.000Z</creationDate>
            <blockDeviceMapping>
                <item>
                    <deviceName>/dev/sda1</deviceName>
                    <ebs>
                        <snapshotId>snap-0aaaaaaaaaaaaaaa1</snapshotId>
                        <volumeSize>8</volumeSize>
                        <volumeType>gp3</volumeType>
                    </ebs>
                </item>
            </blockDeviceMapping>
        </item>
        <item>
            <imageId>ami-def67890</imageId>
            <creationDate>2024-07-15T09:00:00.000Z</creationDate>
            <blockDeviceMapping>
                <item>
                    <deviceName>/dev/xvda</deviceName>
                    <ebs>
                        <snapshotId>snap-0bbbbbbbbbbbbbbb2</snapshotId>
                        <volumeSize>16</volumeSize>
                        <volumeType>gp3</volumeType>
                    </ebs>
                </item>
            </blockDeviceMapping>
        </item>
    </imagesSet>
</DescribeImagesResponse>');

        $client = new MockHttpClient($response);
        $result = new DescribeImagesResult(
            new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()),
            new Ec2Client([], new \AsyncAws\Core\Credentials\NullProvider(), $client),
            new DescribeImagesRequest([])
        );

        $images = iterator_to_array($result->getImages(true));
        self::assertCount(2, $images);
        self::assertContainsOnlyInstancesOf(Image::class, $images);

        self::assertSame('ami-abc12345', $images[0]->getImageId());
        self::assertSame('2024-06-01T12:34:56.000Z', $images[0]->getCreationDate());
        $bdm0 = $images[0]->getBlockDeviceMappings();
        self::assertCount(1, $bdm0);
        self::assertSame('/dev/sda1', $bdm0[0]->getDeviceName());
        self::assertSame('snap-0aaaaaaaaaaaaaaa1', $bdm0[0]->getEbs()->getSnapshotId());
        self::assertSame(8, $bdm0[0]->getEbs()->getVolumeSize());

        self::assertSame('ami-def67890', $images[1]->getImageId());
        self::assertSame('2024-07-15T09:00:00.000Z', $images[1]->getCreationDate());
        self::assertSame('snap-0bbbbbbbbbbbbbbb2', $images[1]->getBlockDeviceMappings()[0]->getEbs()->getSnapshotId());
    }
}
