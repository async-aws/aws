<?php

namespace AsyncAws\Core\Tests\Unit\Input;

use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\PolicyDescriptorType;
use AsyncAws\Core\Sts\Input\Tag;
use AsyncAws\Core\Test\TestCase;

class AssumeRoleRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AssumeRoleRequest([
            'RoleArn' => 'arn:aws::iam::123456789012:role/demo',
            'RoleSessionName' => 'John-session',
            'PolicyArns' => [
                new PolicyDescriptorType(['arn' => 'arn:aws:iam::123456789012:policy/demopolicy1']),
                new PolicyDescriptorType(['arn' => 'arn:aws:iam::123456789012:policy/demopolicy2']),
            ],
            'Policy' => '{"Version":"2012-10-17","Statement":[{"Sid": "Stmt1","Effect": "Allow","Action": "s3:*","Resource": "*"}]}',
            'DurationSeconds' => 1800,
            'Tags' => [new Tag([
                'Key' => 'Project',
                'Value' => 'Pegasus',
            ]), new Tag([
                'Key' => 'Team',
                'Value' => 'Engineering',
            ]), new Tag([
                'Key' => 'Cost-Center',
                'Value' => '12345',
            ])],
            'TransitiveTagKeys' => ['Project', 'Cost-Center'],
            'ExternalId' => '123ABC',
        ]);

        /** @see https://docs.aws.amazon.com/STS/latest/APIReference/API_AssumeRole.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=AssumeRole
            &Version=2011-06-15
            &RoleArn=arn%3Aaws%3A%3Aiam%3A%3A123456789012%3Arole%2Fdemo
            &RoleSessionName=John-session
            &PolicyArns.member.1.arn=arn%3Aaws%3Aiam%3A%3A123456789012%3Apolicy%2Fdemopolicy1
            &PolicyArns.member.2.arn=arn%3Aaws%3Aiam%3A%3A123456789012%3Apolicy%2Fdemopolicy2
            &Policy=%7B%22Version%22%3A%222012-10-17%22%2C%22Statement%22%3A%5B%7B%22Sid%22%3A+%22Stmt1%22%2C%22Effect%22%3A+%22Allow%22%2C%22Action%22%3A+%22s3%3A%2A%22%2C%22Resource%22%3A+%22%2A%22%7D%5D%7D
            &DurationSeconds=1800
            &Tags.member.1.Key=Project
            &Tags.member.1.Value=Pegasus
            &Tags.member.2.Key=Team
            &Tags.member.2.Value=Engineering
            &Tags.member.3.Key=Cost-Center
            &Tags.member.3.Value=12345
            &TransitiveTagKeys.member.1=Project
            &TransitiveTagKeys.member.2=Cost-Center
            &ExternalId=123ABC
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
