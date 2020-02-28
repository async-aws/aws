<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;

/**
 * @see https://docs.aws.amazon.com/lambda/latest/dg/API_AddLayerVersionPermission.html
 */
class AddLayerVersionPermissionRequestTest extends TestCase
{
    /**
     * @var AddLayerVersionPermissionRequest
     */
    private $input;

    public function setUp(): void
    {
        $this->input = new AddLayerVersionPermissionRequest([
            'LayerName' => 'nodejs',
            'VersionNumber' => 2,
            'StatementId' => 'fooBar',
            'Action' => 'lambda:GetLayerVersion',
            'Principal' => '123456789',
            'OrganizationId' => '*',
            'RevisionId' => '123456',
        ]);
        parent::setUp();
    }

    public function testRequestBody(): void
    {
        $expected = '{
           "Action": "lambda:GetLayerVersion",
           "Version": "2015-03-31",
           "StatementId": "fooBar",
           "Principal": "123456789",
           "OrganizationId": "*"
        }';

        self::assertJsonStringEqualsJsonString($expected, $this->input->requestBody());
    }

    public function testRequestUri(): void
    {
        $expected = '/2018-10-31/layers/nodejs/versions/2/policy';

        self::assertSame($expected, $this->input->requestUri());
    }

    public function testRequestQuery(): void
    {
        $expected = ['RevisionId' => '123456'];
        self::assertSame($expected, $this->input->requestQuery());
    }
}
