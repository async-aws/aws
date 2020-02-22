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
        self::markTestIncomplete('Not implemented');

        $input = new AssumeRoleRequest([
            'RoleArn' => 'change me',
            'RoleSessionName' => 'change me',
            'PolicyArns' => [new PolicyDescriptorType([
                'arn' => 'change me',
            ])],
            'Policy' => 'change me',
            'DurationSeconds' => 1337,
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
            'TransitiveTagKeys' => ['change me'],
            'ExternalId' => 'change me',
            'SerialNumber' => 'change me',
            'TokenCode' => 'change me',
        ]);

        $expected = trim('
        Action=AssumeRole
        &Version=2011-06-15
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
