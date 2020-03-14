<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\DeleteObjectsRequest;
use PHPUnit\Framework\TestCase;

class DeleteObjectsRequestTest extends TestCase
{
    public function testRequestBody()
    {
        $input = DeleteObjectsRequest::create(
            [
                'Delete' => [
                    'Objects' => [
                        [
                            'Key' => 'SampleDocument.txt',
                            'VersionId' => 'OYcLXagmS.WaD..oyH4KRguB95_YhLs7',
                        ],
                        [
                            'Key' => 'bar<baz>',
                        ],
                    ],
                    'Quiet' => true,
                ],
            ]
        );

        $expected = '
            <Delete xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
               <Object>
                  <Key>SampleDocument.txt</Key>
                  <VersionId>OYcLXagmS.WaD..oyH4KRguB95_YhLs7</VersionId>
               </Object>
               <Object>
                  <Key>bar&lt;baz&gt;</Key>
               </Object>
               <Quiet>true</Quiet>
            </Delete>
        ';

        self::assertXmlStringEqualsXmlString($expected, $input->request()->getBody()->stringify());
    }
}
