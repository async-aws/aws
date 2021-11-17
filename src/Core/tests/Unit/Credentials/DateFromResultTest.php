<?php

namespace AsyncAws\Core\Tests\Unit\Credentials;

use AsyncAws\Core\Credentials\DateFromResult;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class DateFromResultTest extends TestCase
{
    public function testWithValidDate()
    {
        $httpResponse = $this->getMockBuilder(MockResponse::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHeaders'])
            ->getMock();
        $time = '2020-10-29 17:42:00';
        $httpResponse->expects(self::once())
            ->method('getHeaders')
            ->with(false)
            ->willReturn(['date' => [$time]]);

        $response = new Response($httpResponse, new MockHttpClient(), new NullLogger());
        $result = new DummyResult($response);

        $output = (new DummyCredentials())->expose($result);
        self::assertInstanceOf(\DateTimeImmutable::class, $output);
        self::assertEquals($time, $output->format('Y-m-d H:i:s'));
    }

    public function testWithNoDate()
    {
        $httpResponse = $this->getMockBuilder(MockResponse::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHeaders'])
            ->getMock();

        $httpResponse->expects(self::once())
            ->method('getHeaders')
            ->with(false)
            ->willReturn([]);

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
