<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests;

use PHPUnit\Framework\TestCase;

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
}
