<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\PutObjectAclRequest;
use PHPUnit\Framework\TestCase;

class PutObjectAclRequestTest extends TestCase
{
    public function testRequestBody()
    {
        $input = PutObjectAclRequest::create(
            [
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

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }

    public function testCannedAcl()
    {
        $input = new PutObjectAclRequest(['Bucket' => 'foo-bucket', 'Key' => 'bar-key']);
        $input->setACL('public-read');

        $input->validate();

        $headers = $input->requestHeaders();
        self::assertArrayHasKey('x-amz-acl', $headers);
        self::assertEquals('public-read', $headers['x-amz-acl']);
        self::assertEquals('/foo-bucket/bar-key?acl', $input->requestUri());
        self::assertEmpty($input->requestBody(), 'Request body should be empty when ACL is used');
    }
}
