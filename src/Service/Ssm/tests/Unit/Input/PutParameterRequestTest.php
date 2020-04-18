<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\ValueObject\Tag;

class PutParameterRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutParameterRequest([
            'Name' => 'change me',
            'Description' => 'change me',
            'Value' => 'change me',
            'Type' => 'change me',
            'KeyId' => 'change me',
            'Overwrite' => false,
            'AllowedPattern' => 'change me',
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
            'Tier' => 'change me',
            'Policies' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_PutParameter.html
        $expected = '
                            POST / HTTP/1.0
                            Content-Type: application/x-amz-json-1.0

                            {
            "change": "it"
        }
                        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
