<?php

namespace AsyncAws\S3\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\S3\Enum\Permission;
use AsyncAws\S3\Enum\Type;
use AsyncAws\S3\Input\AccessControlPolicy;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\Grant;
use AsyncAws\S3\Input\Grantee;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\Owner;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Result\AwsObject;
use AsyncAws\S3\Result\CommonPrefix;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\S3Client;
use PHPUnit\Framework\TestCase;

class S3ClientTest extends TestCase
{
    public function testBasicUploadDownload()
    {
        $s3 = $this->getClient();
        $input = new PutObjectRequest();
        $fileBody = 'foobar';
        $input->setBucket('foo')
            ->setKey('bar')
            ->setBody($fileBody);
        $result = $s3->putObject($input);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);

        // Test get object
        $input = new GetObjectRequest();
        $input->setBucket('foo')
            ->setKey('bar');
        $result = $s3->getObject($input);
        $body = $result->getBody()->getContentAsString();

        self::assertEquals($fileBody, $body);

        // Test Delete object
        $result = $s3->deleteObject(['Bucket' => 'foo', 'Key' => 'bar']);
        $result->resolve();
        $info = $result->info();
        self::assertEquals(204, $info['status']);
    }

    public function testCopyObject(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new CopyObjectRequest([
            'Bucket' => 'foo',
            'ContentType' => 'text/plain',
            'CopySource' => 'foo/bar',
            'Key' => 'baz',
        ]);
        $result = $client->CopyObject($input);

        $result->resolve();

        // fetch copyied object
        $result = $client->getObject([
            'Bucket' => 'foo',
            'Key' => 'baz',
        ]);
        self::assertEquals('content', $result->getBody()->getContentAsString());
    }

    public function testCreateBucket(): void
    {
        $client = $this->getClient();

        $input = new CreateBucketRequest([
            'Bucket' => 'qux',
        ]);
        $result = $client->CreateBucket($input);

        $result->resolve();

        // Because of FakeS3, response is null
        self::assertNull($result->getLocation());
    }

    public function testCreateDirectory()
    {
        $s3 = $this->getClient();

        $result = $s3->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar/',
        ]);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);
    }

    public function testDeleteObject(): void
    {
        $client = $this->getClient();

        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ])->resolve();

        $input = new DeleteObjectRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->DeleteObject($input);

        $result->resolve();

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('HTTP/1.1 404 Not Found  returned for "http://localhost:4569/foo/bar".');

        $client->getObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]) - resolve();
    }

    public function testDeleteObjects()
    {
        self::markTestSkipped('The S3 Docker image does not implement DeleteObjects. https://github.com/jubos/fake-s3/issues/97');

        $s3 = $this->getClient();
        $bucket = 'foo';

        $result = $s3->deleteObjects([
            'Bucket' => $bucket,
            'Delete' => ['Objects' => [['Key' => 'foo/bar.txt'], ['Key' => 'foo/bix/xx.txt']]],
        ]);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(204, $info['status']);
    }

    public function testGetFileNotExist()
    {
        $s3 = $this->getClient();

        // Test get object
        $input = new GetObjectRequest();
        $input->setBucket('foo')
            ->setKey('no_file');
        $result = $s3->getObject($input);
        $this->expectException(ClientException::class);
        $result->getBody();
    }

    public function testGetObject(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $result = $client->getObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        self::assertEquals('content', $result->getBody()->getContentAsString());
        self::assertEquals('bytes', $result->getAcceptRanges());
        self::assertSame('7', $result->getContentLength());
        self::assertEquals('"9a0364b9e99bb480dd25e1f0284c8555"', $result->getETag());
        self::assertEquals('application/xml', $result->getContentType());
    }

    public function testGetObjectAcl(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new GetObjectAclRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->GetObjectAcl($input);

        $result->resolve();

        self::assertSame('You', $result->getOwner()->getDisplayName());
        self::assertCount(1, $result->getGrants());
        self::assertSame('FULL_CONTROL', $result->getGrants()[0]->getPermission());
        self::assertSame('You', $result->getGrants()[0]->getGrantee()->getDisplayName());
        self::assertSame('CanonicalUser', $result->getGrants()[0]->getGrantee()->getType());
    }

    public function testHeadObject(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new HeadObjectRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->HeadObject($input);

        $result->resolve();

        self::assertSame('0', $result->getContentLength());
        self::assertEquals('"d41d8cd98f00b204e9800998ecf8427e"', $result->getETag());
    }

    public function testListObjectsV2()
    {
        $s3 = $this->getClient();

        $requests = [];
        for ($i = 0; $i < 5; ++$i) {
            $requests[] = $s3->putObject(['Bucket' => 'foo', 'Key' => 'list/content-' . $i, 'Body' => 'test']);
            $requests[] = $s3->putObject(['Bucket' => 'foo', 'Key' => 'list/prefix-' . $i . '/file']);
        }
        \array_walk($requests, function (PutObjectOutput $response) {
            $response->resolve();
        });

        self::markTestSkipped('The S3 image does not implement Pagination. https://github.com/jubos/fake-s3/issues/223');

        $input = (new ListObjectsV2Request())
            ->setBucket('foo')
            ->setPrefix('list/')
            //->setMaxKeys(2) // pagination is not implemented
            ->setDelimiter('/')
        ;

        $result = $s3->listObjectsV2($input);

        self::assertCount(10, $result);
        self::assertCount(5, $result->getCommonPrefixes());
        self::assertCount(5, $result->getContents());

        $files = \array_map(function (AwsObject $content) {
            return $content->getKey();
        }, \iterator_to_array($result->getContents()));
        $prefixes = \array_map(function (CommonPrefix $prefix) {
            return $prefix->getPrefix();
        }, \iterator_to_array($result->getCommonPrefixes()));

        self::assertContains('list/prefix-1/', $prefixes);
        self::assertContains('list/content-2', $files);
    }

    public function testPutObject(): void
    {
        $client = $this->getClient();

        $input = new PutObjectRequest([
            'Body' => 'content',
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->PutObject($input);

        self::assertEquals('"9a0364b9e99bb480dd25e1f0284c8555"', $result->getETag());
    }

    public function testPutObjectAcl(): void
    {
        $client = $this->getClient();

        $client->PutObject([
            'Body' => 'content',
            'Bucket' => 'foo',
            'Key' => 'bar',
        ])->resolve();

        $input = new PutObjectAclRequest([
            'AccessControlPolicy' => new AccessControlPolicy([
                'Grants' => [new Grant([
                    'Grantee' => new Grantee([
                        'DisplayName' => 'me',
                        'Type' => Type::CANONICAL_USER,
                    ]),
                    'Permission' => Permission::FULL_CONTROL,
                ])],
                'Owner' => new Owner([
                    'DisplayName' => 'me',
                ]),
            ]),
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->PutObjectAcl($input);

        $result->resolve();

        // Not flly Implemented by fakeS3
        self::assertNull($result->getRequestCharged());
    }

    public function testUploadFromClosure()
    {
        $parts = ['some ', 'content'];
        $content = implode('', $parts);
        $index = 0;
        $closure = \Closure::fromCallable(function () use ($parts, &$index) {
            return $parts[$index++] ?? '';
        });

        $s3 = $this->getClient();
        $input = new PutObjectRequest();
        $input->setBucket('foo')
            ->setKey('bar')
            ->setBody($closure);
        $result = $s3->putObject($input);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);

        // Test get object
        $result = $s3->getObject(['Bucket' => 'foo', 'Key' => 'bar']);
        $body = $result->getBody()->getContentAsString();

        self::assertEquals($content, $body);
    }

    public function testUploadFromResource()
    {
        $resource = \fopen('php://temp', 'rw+');
        $content = 'some content';
        fwrite($resource, $content);
        // Dont rewind

        $s3 = $this->getClient();
        $input = new PutObjectRequest();
        $input->setBucket('foo')
            ->setKey('bar')
            ->setBody($resource);
        $result = $s3->putObject($input);

        $result->resolve();
        fclose($resource);

        $info = $result->info();
        self::assertEquals(200, $info['status']);

        // Test get object
        $result = $s3->getObject(['Bucket' => 'foo', 'Key' => 'bar']);
        $body = $result->getBody()->getContentAsString();

        self::assertEquals($content, $body);
    }

    private function getClient(): S3Client
    {
        return new S3Client([
            'endpoint' => 'http://localhost:4569',
        ], new NullProvider());
    }
}
