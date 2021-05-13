<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Tests\Unit;

use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Core\Test\SimpleResultStream;
use AsyncAws\Core\Waiter;
use AsyncAws\Flysystem\S3\AsyncAwsS3Adapter;
use AsyncAws\S3\Enum\StorageClass;
use AsyncAws\S3\Result\CopyObjectOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\ObjectExistsWaiter;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\AwsObject;
use AsyncAws\S3\ValueObject\CommonPrefix;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use PHPUnit\Framework\TestCase;

class AsyncAwsS3AdapterTest extends TestCase
{
    private const BUCKET = 'my_bucket';
    private const PREFIX = 'all-files';

    public static function setUpBeforeClass(): void
    {
        if (!interface_exists(AdapterInterface::class)) {
            self::markTestSkipped('Flysystem v1 is not installed');
        }
    }

    public function testWrite()
    {
        $file = 'foo/bar.txt';

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
                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn(ResultMockFactory::create(PutObjectOutput::class));

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);
        $filesystem->write($file, 'contents', new Config());
    }

    public function testUpdate()
    {
        $path = 'foo/bar.txt';
        $contents = 'contents';
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('upload')
            ->with($path, $contents, $config)
            ->willReturn($return);

        // We test upload function in testWrite.
        $output = $filesystem->update($path, $contents, $config);
        self::assertEquals($return, $output);
    }

    public function testRename()
    {
        $path = 'foo/bar.txt';
        $newPath = 'foo/new.txt';

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['copy', 'delete'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('copy')
            ->with($path, $newPath)
            ->willReturn(true);
        $filesystem->expects(self::once())
            ->method('delete')
            ->with($path)
            ->willReturn(true);

        // We test upload function in testWrite.
        $output = $filesystem->rename($path, $newPath);
        self::assertTrue($output);
    }

    public function testRenameFail()
    {
        $path = 'foo/bar.txt';
        $newPath = 'foo/new.txt';

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['copy', 'delete'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('copy')
            ->with($path, $newPath)
            ->willReturn(false);
        $filesystem->expects(self::never())
            ->method('delete')
            ->with($path);

        // We test upload function in testWrite.
        $output = $filesystem->rename($path, $newPath);
        self::assertFalse($output);
    }

    public function testDelete()
    {
        if (\PHP_VERSION_ID === 70406) {
            self::markTestSkipped('Skipped because of https://bugs.php.net/bug.php?id=79616');
        }

        $path = 'foo/bar.txt';

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

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn(ResultMockFactory::create(DeleteObjectOutput::class));

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->setConstructorArgs([$s3Client,  self::BUCKET, self::PREFIX])
            ->onlyMethods(['has'])
            ->getMock();

        $filesystem->expects(self::once())
            ->method('has')
            ->with($path)
            ->willReturn(false);

        $output = $filesystem->delete($path);
        self::assertTrue($output);
    }

    public function testDeleteDir()
    {
        if (\PHP_VERSION_ID === 70406) {
            self::markTestSkipped('Skipped because of https://bugs.php.net/bug.php?id=79616');
        }

        $path = 'foo';
        $objects = [new AwsObject(['Key' => 'my_key', 'LastModified' => null, 'ETag' => null, 'Size' => null, 'StorageClass' => null, 'Owner' => null])];

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['deleteObjects', 'listObjectsV2'])
            ->getMock();

        $listResult = ResultMockFactory::create(ListObjectsV2Output::class, [
            'Contents' => $objects,
        ]);

        $s3Client->expects(self::once())
            ->method('listObjectsV2')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Prefix'] !== self::PREFIX . '/' . $path . '/') {
                    return false;
                }

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn($listResult);

        $s3Client->expects(self::once())
            ->method('deleteObjects')
            ->with(self::callback(function (array $input) use ($objects) {
                if (\count($input['Delete']['Objects']) !== \count($objects)) {
                    return false;
                }

                if ($input['Delete']['Objects'][0]->getKey() !== $objects[0]->getKey()) {
                    return false;
                }

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn(ResultMockFactory::create(DeleteObjectsOutput::class));

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $output = $filesystem->deleteDir($path);
        self::assertTrue($output);
    }

    public function testCreateDir()
    {
        $path = 'foo/bar';
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('upload')
            ->with($path . '/', '', $config)
            ->willReturn($return);

        $output = $filesystem->createDir($path, $config);
        self::assertEquals($return, $output);
    }

    public function testHasFile()
    {
        $path = 'foo/bar.txt';

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['objectExists'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('objectExists')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Key'] !== self::PREFIX . '/' . $path) {
                    return false;
                }

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn(ResultMockFactory::waiter(ObjectExistsWaiter::class, Waiter::STATE_SUCCESS));

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $output = $filesystem->has($path);
        self::assertTrue($output);
    }

    public function testReadFail()
    {
        $path = 'foo/bar.txt';

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['readObject'])
            ->getMock();

        $filesystem->expects(self::once())
            ->method('readObject')
            ->with($path)
            ->willReturn(false);

        $output = $filesystem->read($path);
        self::assertFalse($output);
    }

    public function testRead()
    {
        $path = 'foo/bar.txt';
        $content = 'my content';

        $result = ResultMockFactory::create(GetObjectOutput::class, [
            'LastModified' => new \DateTimeImmutable(),
            'Body' => new SimpleResultStream($content),
        ]);

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

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $output = $filesystem->read($path);
        self::assertArrayHasKey('type', $output);
        self::assertEquals('file', $output['type']);
        self::assertArrayHasKey('path', $output);
        self::assertEquals($path, $output['path']);
        self::assertArrayHasKey('timestamp', $output);
        self::assertArrayHasKey('contents', $output);

        // Make sure we convert ResultStream
        self::assertIsString($output['contents']);
        self::assertEquals($content, $output['contents']);
    }

    public function testListContents()
    {
        $path = 'foo';

        $result = $this->getMockBuilder(ListObjectsV2Output::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['resolve', 'getIterator', 'getContents', 'getCommonPrefixes'])
            ->getMock();

        $items = [
            new AwsObject(['Key' => self::PREFIX . '/my_key', 'LastModified' => null, 'ETag' => null, 'Size' => null, 'StorageClass' => null, 'Owner' => null]),
            new CommonPrefix(['Prefix' => self::PREFIX . '/common_prefix']),
        ];
        $result->method('getIterator')->willReturn(new class($items) implements \Iterator {
            private $items;

            private $position;

            public function __construct($items)
            {
                $this->items = $items;
                $this->position = 0;
            }

            public function current()
            {
                return $this->items[$this->position];
            }

            public function next()
            {
                ++$this->position;
            }

            public function key()
            {
                return $this->position;
            }

            public function valid()
            {
                return isset($this->items[$this->position]);
            }

            public function rewind()
            {
                $this->position = 0;
            }
        });

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

                if ('/' !== $input['Delimiter']) {
                    return false;
                }

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $outputs = $filesystem->listContents($path);
        $output = $outputs[0];
        self::assertArrayHasKey('type', $output);
        self::assertEquals('file', $output['type']);
        self::assertArrayHasKey('path', $output);
        self::assertEquals('my_key', $output['path']);
    }

    public function testMetadata()
    {
        $path = 'foo/bar.txt';

        $result = ResultMockFactory::create(HeadObjectOutput::class, [
            'LastModified' => new \DateTimeImmutable('2020-03-14 12:00:00', new \DateTimeZone('UTC')),
            'ContentLength' => '123',
            'ContentType' => 'text/plain',
        ]);

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

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $output = $filesystem->getMetadata($path);
        self::assertArrayHasKey('type', $output);
        self::assertEquals('file', $output['type']);
        self::assertArrayHasKey('path', $output);
        self::assertEquals($path, $output['path']);
        self::assertArrayHasKey('timestamp', $output);
        self::assertEquals(1584187200, $output['timestamp']);
        self::assertArrayHasKey('size', $output);
        self::assertEquals('123', $output['size']);
        self::assertArrayHasKey('mimetype', $output);
        self::assertEquals('text/plain', $output['mimetype']);
    }

    public function testGetSize()
    {
        $path = 'foo/bar.txt';
        $return = [
            'size' => '123',
        ];

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('getMetadata')
            ->with($path)
            ->willReturn($return);

        $output = $filesystem->getSize($path);
        self::assertEquals($return, $output);
    }

    public function testMimetype()
    {
        $path = 'foo/bar.txt';
        $return = [
            'mimetype' => 'text/plain',
        ];

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('getMetadata')
            ->with($path)
            ->willReturn($return);

        $output = $filesystem->getMimetype($path);
        self::assertEquals($return, $output);
    }

    public function testTimestamp()
    {
        $path = 'foo/bar.txt';
        $return = [
            'timestamp' => 1584187200,
        ];

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('getMetadata')
            ->with($path)
            ->willReturn($return);

        $output = $filesystem->getTimestamp($path);
        self::assertEquals($return, $output);
    }

    public function testWriteStream()
    {
        $path = 'foo/bar.txt';
        $contents = (new SimpleResultStream('contents'))->getContentAsResource();
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('upload')
            ->with($path, $contents, $config)
            ->willReturn($return);

        // We test upload function in testWrite.
        $output = $filesystem->writeStream($path, $contents, $config);
        self::assertEquals($return, $output);
    }

    public function testUpdateStream()
    {
        $path = 'foo/bar.txt';
        $contents = (new SimpleResultStream('contents'))->getContentAsResource();
        $config = new Config();
        $return = ['foobar'];

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['upload'])
            ->getMock();
        $filesystem->expects(self::once())
            ->method('upload')
            ->with($path, $contents, $config)
            ->willReturn($return);

        // We test upload function in testWrite.
        $output = $filesystem->updateStream($path, $contents, $config);
        self::assertEquals($return, $output);
    }

    public function testReadStream()
    {
        $path = 'foo/bar.txt';
        $content = 'my content';

        $result = ResultMockFactory::create(GetObjectOutput::class, [
            'StorageClass' => StorageClass::STANDARD,
            'Metadata' => [],
            'ContentLength' => (string) \strlen($content),
            'ContentType' => 'text/plain',
            'Body' => new SimpleResultStream($content),
        ]);

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

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $output = $filesystem->readStream($path);
        self::assertArrayHasKey('type', $output);
        self::assertEquals('file', $output['type']);
        self::assertArrayHasKey('path', $output);
        self::assertEquals($path, $output['path']);
        self::assertArrayHasKey('stream', $output);
        self::assertArrayNotHasKey('contents', $output);

        // Make sure we convert ResultStream
        self::assertIsResource($output['stream']);
    }

    public function testCopy()
    {
        $path = 'foo/bar.txt';
        $newPath = 'foo/new.txt';

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['copyObject'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('copyObject')
            ->with(self::callback(function (array $input) use ($path, $newPath) {
                if ($input['Key'] !== self::PREFIX . '/' . $newPath) {
                    return false;
                }

                if ($input['CopySource'] !== self::BUCKET . '/' . self::PREFIX . '/' . $path) {
                    return false;
                }

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                return isset($input['ACL']);
            }))->willReturn(ResultMockFactory::create(CopyObjectOutput::class));

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->setConstructorArgs([$s3Client,  self::BUCKET, self::PREFIX])
            ->onlyMethods(['getRawVisibility'])
            ->getMock();

        $filesystem->expects(self::once())
            ->method('getRawVisibility')
            ->with($path)
            ->willReturn(AdapterInterface::VISIBILITY_PUBLIC);

        $output = $filesystem->copy($path, $newPath);
        self::assertTrue($output);
    }

    public function testSetVisibility()
    {
        $path = 'foo/bar.txt';
        $acl = AdapterInterface::VISIBILITY_PRIVATE;

        $result = ResultMockFactory::create(PutObjectAclOutput::class);

        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['putObjectAcl'])
            ->getMock();

        $s3Client->expects(self::once())
            ->method('putObjectAcl')
            ->with(self::callback(function (array $input) use ($path) {
                if ($input['Key'] !== self::PREFIX . '/' . $path) {
                    return false;
                }

                if (self::BUCKET !== $input['Bucket']) {
                    return false;
                }

                if ('private' !== $input['ACL']) {
                    return false;
                }

                return true;
            }))->willReturn($result);

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $output = $filesystem->setVisibility($path, $acl);
        self::assertArrayHasKey('path', $output);
        self::assertArrayHasKey('visibility', $output);
    }

    public function testGetVisibility()
    {
        $path = 'foo/bar.txt';

        $filesystem = $this->getMockBuilder(AsyncAwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRawVisibility'])
            ->getMock();

        $filesystem->expects(self::once())
            ->method('getRawVisibility')
            ->with($path)
            ->willReturn(AdapterInterface::VISIBILITY_PUBLIC);

        $output = $filesystem->getVisibility($path);
        self::assertIsArray($output);
        self::assertArrayHasKey('visibility', $output);
        self::assertEquals(AdapterInterface::VISIBILITY_PUBLIC, $output['visibility']);
    }

    public function testPathPrefix()
    {
        $s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filesystem = new AsyncAwsS3Adapter($s3Client, self::BUCKET, self::PREFIX);

        $filesystem->setPathPrefix('prefix');
        self::assertEquals('prefix/', $filesystem->getPathPrefix());
        $filesystem->setPathPrefix('prefix/');
        self::assertEquals('prefix/', $filesystem->getPathPrefix());

        $path = 'foo/bar.txt';
        self::assertEquals('prefix/' . $path, $filesystem->applyPathPrefix($path));
        self::assertEquals('prefix/' . $path, $filesystem->applyPathPrefix('/' . $path));
    }
}
