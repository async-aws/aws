<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
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
        $result = new Result(new Response($client->request('POST', 'http://localhost'), $client));

        $this->expectException(ClientException::class);
        unset($result);
    }

    public function testThrowExceptionOnlyOnce()
    {
        $response = new SimpleMockedResponse('Bad request', [], 400);
        $client = new MockHttpClient($response);
        $result = new Result(new Response($client->request('POST', 'http://localhost'), $client));

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
}
