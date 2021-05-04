<?php

namespace AsyncAws\Core\Tests\HttpClient;

use AsyncAws\Core\HttpClient\AwsRetryStrategy;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\AsyncContext;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;

class AwsRetryStrategyTest extends TestCase
{
    /**
     * @dataProvider provideRetries
     */
    public function testShoudRetry(?bool $expected, int $statusCode, ?string $response): void
    {
        if (!class_exists(GenericRetryStrategy::class)) {
            self::markTestSkipped('AwsRetryStrategy requires symfony/http-client 5.2');
        }
        $strategy = new AwsRetryStrategy();
        $context = $this->getContext($statusCode);

        self::assertSame($expected, $strategy->shouldRetry($context, $response, null));
    }

    public function provideRetries(): iterable
    {
        yield [false, 200, null];
        yield [true, 429, null];
        yield [null, 400, null];
        yield [false, 400, 'this is invalid'];
        yield [false, 400, '{"__type": "RandomError"}'];
        yield [true, 400, '{"__type": "RequestLimitExceeded"}'];
    }

    private function getContext(int $statusCode): AsyncContext
    {
        $passthru = null;
        $info = [
            'http_code' => $statusCode,
        ];
        $response = new MockResponse('', $info);

        return new AsyncContext($passthru, new MockHttpClient(), $response, $info, null, 0);
    }
}
