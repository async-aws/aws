<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\GetChangeRequest;

class GetChangeRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetChangeRequest([
            'Id' => 'C2682N5HXP0BZ4',
        ]);

        // see https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetChange.html
        $expected = '
            GET /2013-04-01/change/C2682N5HXP0BZ4 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
