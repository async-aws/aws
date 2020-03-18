<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\DeleteObjectsRequest;

class DeleteObjectsRequestTest extends TestCase
{
    public function testRequest()
    {
        $input = DeleteObjectsRequest::create(
            [
                'Bucket' => 'foo',
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
            POST /foo?delete HTTP/1.0
            Content-Type: application/xml

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

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
