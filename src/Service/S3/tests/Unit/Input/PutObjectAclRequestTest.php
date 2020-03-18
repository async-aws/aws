<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutObjectAclRequest;

class PutObjectAclRequestTest extends TestCase
{
    public function testRequest()
    {
        $input = PutObjectAclRequest::create(
            [
                'Bucket' => 'foo',
                'Key' => 'bar',
                'AccessControlPolicy' => [
                    'Grants' => [
                        [
                            'Permission' => 'FULL_CONTROL',
                            'Grantee' => [
                                'DisplayName' => 'CustomerName@amazon.com',
                                'ID' => '75aa57f09aa0c8caeab4f8c24e99d10f8e7faeeExampleCanonicalUserID',
                                'Type' => 'CanonicalUser',
                            ],
                        ],
                    ],
                    'Owner' => [
                        'ID' => '75aa57f09aa0c8caeab4f8c24e99d10f8e7faeebf76c078efc7c6caea54ba06a',
                        'DisplayName' => 'CustomersName@amazon.com',
                    ],
                ],
            ]
        );

        $expected = '
            PUT /foo/bar?acl HTTP/1.0
            Content-Type: application/xml

            <AccessControlPolicy xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
              <AccessControlList>
                <Grant>
                  <Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="CanonicalUser">
                    <DisplayName>CustomerName@amazon.com</DisplayName>
                    <ID>75aa57f09aa0c8caeab4f8c24e99d10f8e7faeeExampleCanonicalUserID</ID>
                  </Grantee>
                  <Permission>FULL_CONTROL</Permission>
                </Grant>
              </AccessControlList>
              <Owner>
                <DisplayName>CustomersName@amazon.com</DisplayName>
                <ID>75aa57f09aa0c8caeab4f8c24e99d10f8e7faeebf76c078efc7c6caea54ba06a</ID>
              </Owner>
            </AccessControlPolicy>
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testCannedAcl()
    {
        $input = new PutObjectAclRequest(['Bucket' => 'foo-bucket', 'Key' => 'bar-key']);
        $input->setACL('public-read');

        $expected = '
            PUT /foo-bucket/bar-key?acl HTTP/1.0
            Content-Type: application/xml
            x-amz-acl: public-read

        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
