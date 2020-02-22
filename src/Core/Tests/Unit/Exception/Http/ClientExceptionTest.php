<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Exception\Http;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;

class ClientExceptionTest extends TestCase
{
    public function testRestJsonError()
    {
        $json = '{"message":"Missing final \'@domain\'"}';
        $response = new SimpleMockedResponse($json, ['content-type' => 'application/json'], 400);
        $exception = new ClientException($response);

        self::assertEquals('Missing final \'@domain\'', $exception->getAwsMessage());
    }
}
