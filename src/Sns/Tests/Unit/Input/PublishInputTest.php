<?php

declare(strict_types=1);

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Sns\Input\PublishInput;
use PHPUnit\Framework\TestCase;

class PublishInputTest extends TestCase
{
    public function testBody()
    {
        $input = PublishInput::create(['Message' => 'foobar']);
        $body = $input->requestBody();

        self::assertEquals('foobar', $body['Message']);
    }
}
