<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Tests\Unit;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\StreamableBody;
use AsyncAws\Core\Test\SimpleStreamableBody;
use AsyncAws\Flysystem\S3\S3FilesystemV1;
use AsyncAws\Flysystem\S3\S3FilesystemV2;
use AsyncAws\S3\Result\AwsObject;
use AsyncAws\S3\Result\CommonPrefix;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\S3Client;
use League\Flysystem\Config;
use PHPUnit\Framework\TestCase;

class S3FilesystemV1Test extends TestCase
{
    private const BUCKCET = 'my_bucket';
    private const PREFIX = 'all-files';

    public function testWrite()
    {
        $file = 'foo/bar.txt';

        $result = $this->getMockBuilder(PutObjectOutput::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve'])
            ->getMock();

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['putObject'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('putObject')
            ->with(self::callback(function (array $input) use ($file) {
                if ($input['Key'] !== self::PREFIX . '/' . $file) {
                    return false;
                }
                if ('contents' !== $input['Body']) {
                    return false;
                }
                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new S3FilesystemV1($s3Client,  self::BUCKCET, self::PREFIX);
        $filesystem->write($file, 'contents', new Config());
    }

    public function testUpdate()
    {
        $path = 'foo/bar.txt';
        $contents = 'contents';
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('upload')
            ->with($path, $contents, $config)
            ->willReturn($return);

        // We test upload function in testWrite.
        $output = $filesystem->update($path, $contents, $config);
        $this->assertEquals($return, $output);
    }


    public function testRename()
    {
        $path = 'foo/bar.txt';
        $newPath = 'foo/new.txt';

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['copy', 'delete'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('copy')
            ->with($path, $newPath)
            ->willReturn(true);
        $filesystem->expects($this->once())
            ->method('delete')
            ->with($path)
            ->willReturn(true);

        // We test upload function in testWrite.
        $output = $filesystem->rename($path, $newPath);
        $this->assertTrue($output);
    }


    public function testRenameFail()
    {
        $path = 'foo/bar.txt';
        $newPath = 'foo/new.txt';

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['copy', 'delete'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('copy')
            ->with($path, $newPath)
            ->willReturn(false);
        $filesystem->expects($this->never())
            ->method('delete')
            ->with($path);

        // We test upload function in testWrite.
        $output = $filesystem->rename($path, $newPath);
        $this->assertFalse($output);
    }

    public function testDelete()
    {
        $path = 'foo/bar.txt';

        $result = $this->getMockBuilder(DeleteObjectOutput::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve'])
            ->getMock();

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['deleteObject'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('deleteObject')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Key'] !== self::PREFIX . '/' . $path) {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->setConstructorArgs([$s3Client,  self::BUCKCET, self::PREFIX])
            ->onlyMethods(['has'])
            ->getMock();

        $filesystem->expects($this->once())
            ->method('has')
            ->with($path)
            ->willReturn(false);

        $output = $filesystem->delete($path);
        $this->assertTrue($output);
    }

    public function testDeleteDir()
    {
        $path = 'foo';
        $objects = [new AwsObject(['Key'=>'my_key', 'LastModified'=>null, 'ETag'=>null, 'Size'=>null, 'StorageClass'=>null, 'Owner'=>null])];

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['deleteObjects', 'listObjectsV2'])
            ->getMock();

        $listResult = $this->getMockBuilder(ListObjectsV2Output::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContents'])
            ->getMock();

        $listResult->method('getContents')
            ->willReturn($objects);

        $s3Client->expects(self::once())
            ->method('listObjectsV2')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Prefix'] !== self::PREFIX . '/' . $path . '/') {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($listResult);


        $deleteResult = $this->getMockBuilder(DeleteObjectsOutput::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('deleteObjects')
            ->with(self::callback(function (array $input) use ($objects) {
                if (count($input['Delete']['Objects']) !== count($objects)) {
                    return false;
                }

                if ($input['Delete']['Objects'][0]->getKey() !== $objects[0]->getKey()) {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($deleteResult);

        $filesystem = new S3FilesystemV1($s3Client,  self::BUCKCET, self::PREFIX);

        $output = $filesystem->deleteDir($path);
        $this->assertTrue($output);
    }

    public function testCreateDir()
    {
        $path = 'foo/bar';
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('upload')
            ->with($path.'/', '', $config)
            ->willReturn($return);

        $output = $filesystem->createDir($path, $config);
        $this->assertEquals($return, $output);
    }

    public function testHasFile()
    {
        $path = 'foo/bar.txt';

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getObject'])
            ->getMock();

        $result = $this->getMockBuilder(GetObjectOutput::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('getObject')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Key'] !== self::PREFIX . '/' . $path) {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new S3FilesystemV1($s3Client, self::BUCKCET, self::PREFIX);

        $output = $filesystem->has($path);
        $this->assertTrue($output);
    }

    public function testReadFail()
    {
        $path = 'foo/bar.txt';

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['readObject'])
            ->getMock();

        $filesystem->expects($this->once())
            ->method('readObject')
            ->with($path)
            ->willReturn(false);

        $output = $filesystem->read($path);
        $this->assertFalse($output);
    }

    public function testRead()
    {
        $path = 'foo/bar.txt';
        $content = 'my content';

        $result = $this->getMockBuilder(GetObjectOutput::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve', 'getLastModified', 'getBody'])
            ->getMock();

        $result->method('getLastModified')->willReturn(new \DateTimeImmutable());
        $result->method('getBody')->willReturn(new SimpleStreamableBody($content));

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getObject'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('getObject')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Key'] !== self::PREFIX . '/' . $path) {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new S3FilesystemV1($s3Client, self::BUCKCET, self::PREFIX);

        $output = $filesystem->read($path);
        $this->assertArrayHasKey('type', $output);
        $this->assertEquals('file', $output['type']);
        $this->assertArrayHasKey('path', $output);
        $this->assertEquals($path, $output['path']);
        $this->assertArrayHasKey('timestamp', $output);
        $this->assertArrayHasKey('contents', $output);

        // Make sure we convert StreamableBodyInterface
        $this->assertIsString($output['contents']);
        $this->assertEquals($content, $output['contents']);
    }

    public function testListContents()
    {
        $path = 'foo';

        $result = $this->getMockBuilder(ListObjectsV2Output::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve', 'getIterator', 'getContents', 'getCommonPrefixes'])
            ->getMock();

        $result->method('getIterator')->willReturn(new class($result) implements \Iterator {
            private $item;
            private $position;

            public function __construct($item)
            {
                $this->item = [$item];
                $this->position = 0;
            }

            public function current()
            {
                return $this->item[$this->position];
            }

            public function next()
            {
                $this->position++;
            }

            public function key()
            {
                return $this->position;
            }

            public function valid()
            {
                return isset($this->item[$this->position]);
            }

            public function rewind()
            {
                $this->position = 0;
            }

        });
        $result->method('getContents')->willReturn([new AwsObject(['Key'=>self::PREFIX.'/my_key', 'LastModified'=>null, 'ETag'=>null, 'Size'=>null, 'StorageClass'=>null, 'Owner'=>null])]);
        $result->method('getCommonPrefixes')->willReturn([new CommonPrefix(['Prefix'=>self::PREFIX.'/common_prefix'])]);

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['listObjectsV2'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('listObjectsV2')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Prefix'] !== self::PREFIX . '/' . $path . '/') {
                    return false;
                }

                if ($input['Delimiter'] !== '/') {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new S3FilesystemV1($s3Client, self::BUCKCET, self::PREFIX);

        $outputs = $filesystem->listContents($path);
        $output = $outputs[0];
        $this->assertArrayHasKey('type', $output);
        $this->assertEquals('file', $output['type']);
        $this->assertArrayHasKey('path', $output);
        $this->assertEquals('my_key', $output['path']);
    }



    public function testMetadata()
    {
        $path = 'foo/bar.txt';

        $result = $this->getMockBuilder(HeadObjectOutput::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve', 'getLastModified', 'getContentLength', 'getContentType'])
            ->getMock();

        $result->method('getLastModified')->willReturn(new \DateTimeImmutable('2020-03-14 12:00:00'));
        $result->method('getContentLength')->willReturn('123');
        $result->method('getContentType')->willReturn('text/plain');

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['headObject'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('headObject')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Key'] !== self::PREFIX . '/' . $path) {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new S3FilesystemV1($s3Client, self::BUCKCET, self::PREFIX);

        $output = $filesystem->getMetadata($path);
        $this->assertArrayHasKey('type', $output);
        $this->assertEquals('file', $output['type']);
        $this->assertArrayHasKey('path', $output);
        $this->assertEquals($path, $output['path']);
        $this->assertArrayHasKey('timestamp', $output);
        $this->assertEquals(1584187200, $output['timestamp']);
        $this->assertArrayHasKey('size', $output);
        $this->assertEquals('123', $output['size']);
        $this->assertArrayHasKey('mimetype', $output);
        $this->assertEquals('text/plain', $output['mimetype']);

    }


    public function testGetSize()
    {
        $path = 'foo/bar.txt';
        $return = [
            'size' => '123',
        ];

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('getMetadata')
            ->with($path)
            ->willReturn($return);

        $output = $filesystem->getSize($path);
        $this->assertEquals(123, $output);
    }

    public function testMimetype()
    {
        $path = 'foo/bar.txt';
        $return = [
            'mimetype' => 'text/plain',
        ];

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('getMetadata')
            ->with($path)
            ->willReturn($return);

        $output = $filesystem->getSize($path);
        $this->assertEquals('text/plain', $output);
    }

    public function testTimestamp()
    {
        $path = 'foo/bar.txt';
        $return = [
            'timestamp' => 1584187200,
        ];

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('getMetadata')
            ->with($path)
            ->willReturn($return);

        $output = $filesystem->getSize($path);
        $this->assertEquals(1584187200, $output);
    }


    public function testWriteStream()
    {
        $path = 'foo/bar.txt';
        $contents = (new SimpleStreamableBody('contents'))->getContentAsResource();
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('upload')
            ->with($path, $contents, $config)
            ->willReturn($return);

        // We test upload function in testWrite.
        $output = $filesystem->writeStream($path, $contents, $config);
        $this->assertEquals($return, $output);
    }


    public function testUpdateStream()
    {
        $path = 'foo/bar.txt';
        $contents = (new SimpleStreamableBody('contents'))->getContentAsResource();
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(S3FilesystemV1::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects($this->once())
            ->method('upload')
            ->with($path, $contents, $config)
            ->willReturn($return);

        // We test upload function in testWrite.
        $output = $filesystem->updateStream($path, $contents, $config);
        $this->assertEquals($return, $output);
    }

    public function testReadStream()
    {
        $path = 'foo/bar.txt';
        $content = 'my content';

        $result = $this->getMockBuilder(GetObjectOutput::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve', 'getLastModified', 'getBody'])
            ->getMock();

        $result->method('getBody')->willReturn(new SimpleStreamableBody($content));

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getObject'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('getObject')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Key'] !== self::PREFIX . '/' . $path) {
                    return false;
                }

                if ($input['Bucket'] !== self::BUCKCET) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new S3FilesystemV1($s3Client, self::BUCKCET, self::PREFIX);

        $output = $filesystem->readStream($path);
        $this->assertArrayHasKey('type', $output);
        $this->assertEquals('file', $output['type']);
        $this->assertArrayHasKey('path', $output);
        $this->assertEquals($path, $output['path']);
        $this->assertArrayHasKey('stream', $output);
        $this->assertArrayNotHasKey('contents', $output);

        // Make sure we convert StreamableBodyInterface
        $this->assertIsResource($output['stream']);
    }


    public function testCopy()
    {
    }


    public function testSetVisibility()
    {
    }
}
