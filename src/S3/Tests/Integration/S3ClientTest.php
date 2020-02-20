<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Integration;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Result\AwsObject;
use AsyncAws\S3\Result\CommonPrefix;
use AsyncAws\S3\Result\PutObjectOutput;
use PHPUnit\Framework\TestCase;

class S3ClientTest extends TestCase
{
    use GetClient;

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

    public function testUploadFromClosure()
    {
        $content = 'some content';
        $closure = \Closure::fromCallable(function () use ($content) {
            return $content;
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

        self::markTestIncomplete('The S3 image does not implement Pagination. https://github.com/jubos/fake-s3/issues/223');

        $input = (new ListObjectsV2Request())
            ->setBucket('foo')
            ->setPrefix('list/')
//            ->setMaxKeys(2) // pagination is not implemented
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

    public function testDeleteObjects()
    {
        self::markTestSkipped('The S3 image does not implement DeleteObjects. https://github.com/jubos/fake-s3/issues/97');

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
}
