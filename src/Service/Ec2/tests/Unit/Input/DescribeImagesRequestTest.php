<?php

namespace AsyncAws\Ec2\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ec2\Input\DescribeImagesRequest;
use AsyncAws\Ec2\ValueObject\Filter;

class DescribeImagesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeImagesRequest([
            'Owners' => ['amazon', 'self'],
            'Filters' => [
                new Filter(['Name' => 'tag:Name', 'Values' => ['prod-image-1', 'prod-image-2']]),
                new Filter(['Name' => 'state', 'Values' => ['available']]),
            ],
        ]);

        // EC2 wire format (ec2 query protocol):
        //  - Owners parent locationName "Owner" -> Owner.$index
        //  - Filters parent locationName "Filter" -> Filter.$index
        //  - Filter.Values parent locationName "Value" -> Filter.$i.Value.$j
        //  - ':' URL-encoded to %3A
        // see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeImages.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DescribeImages
            &Version=2016-11-15
            &Owner.1=amazon
            &Owner.2=self
            &Filter.1.Name=tag%3AName
            &Filter.1.Value.1=prod-image-1
            &Filter.1.Value.2=prod-image-2
            &Filter.2.Name=state
            &Filter.2.Value.1=available
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestWithImageIds(): void
    {
        $input = new DescribeImagesRequest([
            'ImageIds' => ['ami-abc12345', 'ami-def67890'],
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DescribeImages
            &Version=2016-11-15
            &ImageId.1=ami-abc12345
            &ImageId.2=ami-def67890
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestEmpty(): void
    {
        $input = new DescribeImagesRequest([]);

        // EC2 protocol emits nothing for empty/unset list params.
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DescribeImages
            &Version=2016-11-15
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
