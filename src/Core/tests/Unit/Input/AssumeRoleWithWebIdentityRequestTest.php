<?php

namespace AsyncAws\Core\Tests\Unit\Input;

use AsyncAws\Core\Sts\Input\AssumeRoleWithWebIdentityRequest;
use AsyncAws\Core\Sts\ValueObject\PolicyDescriptorType;
use AsyncAws\Core\Test\TestCase;

class AssumeRoleWithWebIdentityRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AssumeRoleWithWebIdentityRequest([
            'RoleArn' => 'arn:aws:iam::123456789012:role/FederatedWebIdentityRole',
            'RoleSessionName' => 'app1',
            'WebIdentityToken' => 'FooBarBaz',
            'ProviderId' => 'www.amazon.com',
            'PolicyArns' => [new PolicyDescriptorType([
                'arn' => 'arn:aws:iam::123456789012:policy/q=webidentitydemopolicy1',
            ]), new PolicyDescriptorType([
                'arn' => 'arn:aws:iam::123456789012:policy/webidentitydemopolicy2',
            ])],
            'DurationSeconds' => 3600,
        ]);

        // see https://docs.aws.amazon.com/STS/latest/APIReference/API_AssumeRoleWithWebIdentity.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=AssumeRoleWithWebIdentity
            &Version=2011-06-15
            &DurationSeconds=3600
            &PolicyArns.member.1.arn=arn%3Aaws%3Aiam%3A%3A123456789012%3Apolicy%2Fq%3Dwebidentitydemopolicy1
            &PolicyArns.member.2.arn=arn%3Aaws%3Aiam%3A%3A123456789012%3Apolicy%2Fwebidentitydemopolicy2
            &ProviderId=www.amazon.com
            &RoleSessionName=app1
            &RoleArn=arn%3Aaws%3Aiam%3A%3A123456789012%3Arole%2FFederatedWebIdentityRole
            &WebIdentityToken=FooBarBaz
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
