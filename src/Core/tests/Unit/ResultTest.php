<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ResultTest extends TestCase
{
    /**
     * Asserts the logic generated in PopulateResultXml take care of Xml Fields.
     */
    public function testPopulateResultXml()
    {
        $xml = '
<Root>
    <String>Foo</String>
    <EmptyString></EmptyString>
    <Int>1337</Int>
    <EmptyInt>O</EmptyInt>
    <BoolTrue>true</BoolTrue>
    <BoolFalse>false</BoolFalse>
    <Object>
        <Sub></Sub>
    </Object>
    <EmptyObject/>
</Root>';

        $data = new \SimpleXMLElement($xml);
        self::assertTrue($data->String ? true : false);
        self::assertSame('Foo', (string) $data->String);
        self::assertTrue($data->EmptyString ? true : false);
        self::assertSame('', (string) $data->EmptyString);

        self::assertTrue($data->Int ? true : false);
        self::assertSame(1337, (int) (string) $data->Int);
        self::assertTrue($data->EmptyInt ? true : false);
        self::assertSame(0, (int) (string) $data->EmptyInt);

        self::assertTrue($data->BoolTrue ? true : false);
        self::assertTrue('true' === (string) $data->BoolTrue);
        self::assertTrue($data->BoolFalse ? true : false);
        self::assertFalse('true' === (string) $data->BoolFalse);

        self::assertTrue($data->Object ? true : false);
        self::assertTrue($data->EmptyObject ? true : false);
        self::assertFalse($data->DoesNotExists ? true : false);
    }

    public function testThrowExceptionDestruct()
    {
        $response = new SimpleMockedResponse('Bad request', [], 400);
        $client = new MockHttpClient($response);
        $result = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $this->expectException(ClientException::class);
        unset($result);
    }

    public function testThrowExceptionOnlyOnce()
    {
        $response = new SimpleMockedResponse('Bad request', [], 400);
        $client = new MockHttpClient($response);
        $result = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        try {
            $result->resolve();
            self::fail('resolve should throw exception.');
        } catch (ClientException $e) {
        }

        // This should not be an exception.
        try {
            unset($result);
            self::assertTrue(true);
        } catch (ClientException $e) {
            self::fail('A resolved result should not throw exception on destruct.');
        }
    }

    public function testWait()
    {
        $results = [];
        $client = new MockHttpClient((function () { while (true) { yield new SimpleMockedResponse('OK', [], 200); } })());
        for ($i = 0; $i < 10; ++$i) {
            $results[] = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        }

        $counter = 0;
        foreach (Result::wait($results) as $index => $result) {
            self::assertTrue($result->info()['resolved']);
            self::assertFalse($result->info()['body_downloaded']);
            self::assertSame($results[$index], $result);
            ++$counter;
        }
        self::assertSame(10, $counter);
    }

    public function testWaitWithMixException()
    {
        $client = new MockHttpClient([new SimpleMockedResponse('Bad request', [], 400), new SimpleMockedResponse('OK', [], 200)]);
        $result1 = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        $result2 = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $counter = 0;
        foreach (Result::wait([$result1, $result2]) as $result) {
            self::assertTrue($result->info()['resolved']);
            ++$counter;
        }
        self::assertSame(2, $counter);

        $this->expectException(HttpException::class);
        $result1->resolve();
    }

    public function testWaitWithExceptionOnDestruct()
    {
        $client = new MockHttpClient(new SimpleMockedResponse('Bad request', [], 400));
        $result = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        foreach (Result::wait([$result]) as $r) {
            self::assertTrue($r->info()['resolved']);
        }

        $this->expectException(HttpException::class);
        unset($result, $r);
    }

    public function testWaitWithBody()
    {
        $client = new MockHttpClient(new SimpleMockedResponse('OK', [], 200));
        $result = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        foreach (Result::wait([$result], null, true) as $result) {
            self::assertTrue($result->info()['resolved']);
            self::assertTrue($result->info()['body_downloaded']);
        }
    }

    public function testWaitWithBodyAfterManuallyResolve()
    {
        $client = new MockHttpClient(new SimpleMockedResponse('OK', [], 200));
        $result = new Result(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $result->resolve();
        self::assertTrue($result->info()['resolved']);
        self::assertFalse($result->info()['body_downloaded']);

        foreach (Result::wait([$result], null, true) as $result) {
            self::assertTrue($result->info()['body_downloaded']);
        }
    }
}
