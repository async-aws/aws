<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\DeleteHostedZoneRequest;

class DeleteHostedZoneRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteHostedZoneRequest([
            'Id' => 'Z1D633PEXAMPLE',
        ]);

        // see https://docs.aws.amazon.com/Route53/latest/APIReference/API_DeleteHostedZone.html
        $expected = '
            DELETE /2013-04-01/hostedzone/Z1D633PEXAMPLE HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
