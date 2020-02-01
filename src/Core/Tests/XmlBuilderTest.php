<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests;

use AsyncAws\Core\XmlBuilder;
use PHPUnit\Framework\TestCase;

class XmlBuilderTest extends TestCase
{
    public function testGetXml()
    {
        $data = ['foo' => 'bar'];
        $config = [
            'parent' => ['type'=>'structure', 'members'=>[
                'foo' => ['shape'=>'FooShape', 'locationName'=>'foo_name']
            ]],
            'FooShape' => ['type' => 'string'],
            '_root' => ['type' => 'parent','xmlName' => 'parent_name','uri' => 'http://s3.amazonaws.com/doc/2006-03-01/']
        ];

        $builder = new XmlBuilder($data, $config);
        $output = $builder->getXml();

        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<parent_name xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
  <foo_name>bar</foo_name>
</parent_name>

XML;

        self::assertEquals($expected, $output);
    }

    public function testIgnoreExtraData()
    {
        $data = ['foo' => 'bar', 'Action'=>'my_action'];
        $config = [
            'parent' => ['type'=>'structure', 'members'=>[
                'foo' => ['shape'=>'FooShape', 'locationName'=>'foo_name']
            ]],
            'FooShape' => ['type' => 'string'],
            '_root' => ['type' => 'parent','xmlName' => 'parent_name','uri' => 'http://s3.amazonaws.com/doc/2006-03-01/']
        ];

        $builder = new XmlBuilder($data, $config);
        $output = $builder->getXml();

        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<parent_name xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
  <foo_name>bar</foo_name>
</parent_name>

XML;

        self::assertEquals($expected, $output);
    }
}
