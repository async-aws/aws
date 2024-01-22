<?php

namespace AsyncAws\Core\Tests\Unit\Credentials;

use AsyncAws\Core\Credentials\DateFromResult;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DateFromResultTest extends TestCase
{
    public function testWithValidDate()
    {
        $time = '2020-10-29 17:42:00';
        $httpResponse = new SimpleMockedResponse('{"foo":"bar"}', ['date' => $time], 200);

        $response = new Response($httpResponse, new MockHttpClient(), new NullLogger());
        $result = new DummyResult($response);

        $output = (new DummyCredentials())->expose($result);
        self::assertInstanceOf(\DateTimeImmutable::class, $output);
        self::assertEquals($time, $output->format('Y-m-d H:i:s'));
    }

    public function testWithNoDate()
    {
        $httpResponse = new SimpleMockedResponse('{"foo":"bar"}', [], 200);

        $response = new Response($httpResponse, new MockHttpClient(), new NullLogger());
        $result = new DummyResult($response);

        $output = (new DummyCredentials())->expose($result);
        self::assertNull($output);
    }
}

class DummyResult extends Result
{
}

class DummyCredentials
{
    use DateFromResult;

    public function expose(Result $result)
    {
        return $this->getDateFromResult($result);
    }
}
