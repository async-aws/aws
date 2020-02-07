<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Integration;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use PHPUnit\Framework\TestCase;

class S3ClientTest extends TestCase
{
    use GetClient;

    public function testBasicUploadDownload()
    {
        $s3 = $this->getClient();
        $input = new PutObjectRequest([]);
        $fileBody = 'foobar';
        $input->setBucket('foo')
            ->setKey('bar')
            ->setBody($fileBody);
        $result = $s3->putObject($input);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);

        // Test get object
        $input = new GetObjectRequest([]);
        $input->setBucket('foo')
            ->setKey('bar');
        $result = $s3->getObject($input);
        $body = $result->getBody()->getContentAsString();

        self::assertEquals($fileBody, $body);

        // Test Delete object
        $result = $s3->deleteObject(['Bucket' => 'foo', 'Key' => 'bar']);
        $info = $result->resolve();
        $info = $result->info();
        self::assertEquals(204, $info['status']);
    }

    public function testGetFileNotExist()
    {
        $s3 = $this->getClient();

        // Test get object
        $input = new GetObjectRequest([]);
        $input->setBucket('foo')
            ->setKey('no_file');
        $result = $s3->getObject($input);
        $this->expectException(ClientException::class);
        $result->getBody();
    }
}
