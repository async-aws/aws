<?php

namespace AsyncAws\Core\Tests\Unit\Input;

use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\PolicyDescriptorType;
use AsyncAws\Core\Sts\Input\Tag;
use PHPUnit\Framework\TestCase;

class AssumeRoleRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new AssumeRoleRequest([
            'RoleArn' => 'arn:aws::iam::123456789012:role/demo',
            'RoleSessionName' => 'John-session',
            'Policy' => '{"Version":"2012-10-17","Statement":[{"Sid": "Stmt1","Effect": "Allow","Action": "s3:*","Resource": "*"}]}',
            'DurationSeconds' => 1800,
            'Tags' => [new Tag([
                'Key' => 'Project',
                'Value' => 'Pegasus',
            ]), new Tag([
                'Key' => 'Cost-Center',
                'Value' => '12345',
            ])],
            'ExternalId' => '123ABC',
        ]);

        /** @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_request.html */
        $expected = trim('
Action=AssumeRole
&Version=2011-06-15
&RoleArn=arn%3Aaws%3A%3Aiam%3A%3A123456789012%3Arole%2Fdemo
&RoleSessionName=John-session
&Policy=%7B%22Version%22%3A%222012-10-17%22%2C%22Statement%22%3A%5B%7B%22Sid%22%3A+%22Stmt1%22%2C%22Effect%22%3A+%22Allow%22%2C%22Action%22%3A+%22s3%3A%2A%22%2C%22Resource%22%3A+%22%2A%22%7D%5D%7D
&DurationSeconds=1800
&Tags.member.1.Key=Project
&Tags.member.1.Value=Pegasus
&Tags.member.2.Key=Cost-Center
&Tags.member.2.Value=12345
&ExternalId=123ABC
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
