<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Tests\Unit;

use AsyncAws\Flysystem\S3\S3FilesystemV1;
use AsyncAws\Flysystem\S3\S3FilesystemV2;
use AsyncAws\S3\Result\DeleteObjectOutput;
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
    }

    public function testWriteStream()
    {
    }


    public function testUpdateStream()
    {
    }


    public function testCopy()
    {
    }


    public function testCreateDir()
    {
    }

    public function testSetVisibility()
    {
    }
}
