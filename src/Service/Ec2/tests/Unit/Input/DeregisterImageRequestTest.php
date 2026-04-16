<?php

namespace AsyncAws\Ec2\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ec2\Input\DeregisterImageRequest;

class DeregisterImageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeregisterImageRequest([
            'ImageId' => 'ami-abc12345',
            'DryRun' => true,
        ]);

        // Note: DryRun has locationName "dryRun" -> ucfirst -> "DryRun"
        // see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DeregisterImage.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DeregisterImage
            &Version=2016-11-15
            &ImageId=ami-abc12345
            &DryRun=true
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
