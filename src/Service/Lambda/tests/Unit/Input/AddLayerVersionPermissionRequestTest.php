<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;

/**
 * @see https://docs.aws.amazon.com/lambda/latest/dg/API_AddLayerVersionPermission.html
 */
class AddLayerVersionPermissionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AddLayerVersionPermissionRequest([
            'LayerName' => 'nodejs',
            'VersionNumber' => 2,
            'StatementId' => 'fooBar',
            'Action' => 'lambda:GetLayerVersion',
            'Principal' => '123456789',
            'OrganizationId' => '*',
            'RevisionId' => '123456',
        ]);

        $expected = '
            POST /2018-10-31/layers/nodejs/versions/2/policy?RevisionId=123456 HTTP/1.0
            Content-Type: application/json

            {
               "Action": "lambda:GetLayerVersion",
               "StatementId": "fooBar",
               "Principal": "123456789",
               "OrganizationId": "*"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
