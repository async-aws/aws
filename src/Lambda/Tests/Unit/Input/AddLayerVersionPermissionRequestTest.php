<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;

class AddLayerVersionPermissionRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new AddLayerVersionPermissionRequest([
            'LayerName' => 'change me',
            'VersionNumber' => 1337,
            'StatementId' => 'change me',
            'Action' => 'change me',
            'Principal' => 'change me',
            'OrganizationId' => 'change me',
            'RevisionId' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/Lambda/latest/APIReference/API_AddLayerVersionPermission.html
        $expected = '{
            "change": "it"
        }';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
