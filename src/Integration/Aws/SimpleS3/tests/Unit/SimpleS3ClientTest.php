<?php

declare(strict_types=1);

namespace AsyncAws\SimpleS3\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\S3\Input\CompleteMultipartUploadRequest;
use AsyncAws\S3\Result\CreateMultipartUploadOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\UploadPartCopyOutput;
use AsyncAws\S3\ValueObject\CopyPartResult;
use AsyncAws\SimpleS3\SimpleS3Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SimpleS3ClientTest extends TestCase
{
    public function testGetUrlHostStyle()
    {
        $client = new SimpleS3Client(['region' => 'eu-central-1'], new NullProvider(), new MockHttpClient());
        $url = $client->getUrl('bucket', 'images/file.jpg');
        self::assertSame('https://bucket.s3.eu-central-1.amazonaws.com/images/file.jpg', $url);
    }

    public function testGetUrlPathStyle()
    {
        $client = new SimpleS3Client(['region' => 'eu-central-1', 'pathStyleEndpoint' => true], new NullProvider(), new MockHttpClient());
        $url = $client->getUrl('bucket', 'images/file.jpg');
        self::assertSame('https://s3.eu-central-1.amazonaws.com/bucket/images/file.jpg', $url);
    }

    public function testGetPresignedUrl()
    {
        $client = new SimpleS3Client(['region' => 'eu-central-1'], new NullProvider(), new MockHttpClient());
        $url = $client->getPresignedUrl('bucket', 'images/file.jpg');
        self::assertSame('https://bucket.s3.eu-central-1.amazonaws.com/images/file.jpg', $url);
        $urlWithVersion = $client->getPresignedUrl('bucket', 'images/file.jpg', null, 'abc123');
        self::assertSame('https://bucket.s3.eu-central-1.amazonaws.com/images/file.jpg?versionId=abc123', $urlWithVersion);
    }

    public function testUploadSmallFileString()
    {
        $object = 'User-agent: *\nDisallow:';
        $bucket = 'bucket';
        $file = 'robots.txt';
        $callback = function (array $input) use ($bucket, $file, $object) {
            self::assertEquals($bucket, $input['Bucket']);
            self::assertEquals($file, $input['Key']);
            self::assertEquals($object, $input['Body']);

            return true;
        };

        self::assertSmallFileUpload($callback, $bucket, $file, $object);
    }

    public function testUploadSmallFileResource()
    {
        $bucket = 'bucket';
        $file = 'robots.txt';
        $object = fopen('php://temp', 'rw+');
        fwrite($object, 'User-agent: *\nDisallow:');
        // do not rewind

        $callback = function (array $input) use ($bucket, $file, $object) {
            self::assertEquals($bucket, $input['Bucket']);
            self::assertEquals($file, $input['Key']);
            self::assertEquals($object, $input['Body']);

            return true;
        };

        self::assertSmallFileUpload($callback, $bucket, $file, $object);
    }

    public function testUploadSmallFileClosure()
    {
        $contents = 'User-agent: *\nDisallow:';
        $bucket = 'bucket';
        $file = 'robots.txt';
        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, $contents);
        fseek($resource, 0, \SEEK_SET);

        $callback = function (array $input) use ($bucket, $file, $contents) {
            self::assertEquals($bucket, $input['Bucket']);
            self::assertEquals($file, $input['Key']);
            self::assertIsResource($input['Body']);

            fseek($input['Body'], 0, \SEEK_SET);
            self::assertEquals($contents, stream_get_contents($input['Body']));

            return true;
        };

        self::assertSmallFileUpload($callback, $bucket, $file, static function (int $length) use ($resource): string {
            return fread($resource, $length);
        });
    }

    public function testUploadSmallFileIterable()
    {
        $contents = [
            sha1('foo'),
            sha1('bar'),
            sha1('baz'),
        ];
        $bucket = 'bucket';
        $file = 'robots.txt';

        $callback = function (array $input) use ($bucket, $file, $contents) {
            self::assertEquals($bucket, $input['Bucket']);
            self::assertEquals($file, $input['Key']);
            fseek($input['Body'], 0, \SEEK_SET);
            self::assertEquals(implode('', $contents), stream_get_contents($input['Body']));

            return true;
        };

        self::assertSmallFileUpload($callback, $bucket, $file, (static function () use ($contents): iterable {
            foreach ($contents as $data) {
                yield $data;
            }
        })());
    }

    public function testUploadSmallFileEmptyClosure()
    {
        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'putObject', 'completeMultipartUpload'])
            ->getMock();

        $s3->expects(self::never())->method('createMultipartUpload');
        $s3->expects(self::never())->method('abortMultipartUpload');
        $s3->expects(self::never())->method('completeMultipartUpload');
        $s3->expects(self::once())->method('putObject');

        $s3->upload('bucket', 'robots.txt', static function (int $length): string {
            return '';
        });
    }

    public function testCopySmallFile()
    {
        $megabyte = 1024 * 1024;
        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'copyObject', 'completeMultipartUpload', 'headObject'])
            ->getMock();

        $s3->expects(self::never())->method('createMultipartUpload');
        $s3->expects(self::never())->method('abortMultipartUpload');
        $s3->expects(self::never())->method('completeMultipartUpload');
        $s3->expects(self::once())->method('copyObject');
        $s3->expects(self::once())->method('headObject')
            ->willReturn(ResultMockFactory::create(HeadObjectOutput::class, ['ContentLength' => 50 * $megabyte]));

        $s3->copy('bucket', 'robots.txt', 'bucket', 'copy-robots.txt');
    }

    public function testCopyLargeFile()
    {
        $megabyte = 1024 * 1024;
        $uploadedParts = 0;
        $completedParts = 0;

        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'copyObject', 'completeMultipartUpload', 'uploadPartCopy', 'headObject'])
            ->getMock();

        $s3->expects(self::once())->method('createMultipartUpload')
            ->willReturn(ResultMockFactory::create(CreateMultipartUploadOutput::class, ['UploadId' => '4711']));
        $s3->expects(self::never())->method('abortMultipartUpload');
        $s3->expects(self::never())->method('copyObject');
        $s3->expects(self::any())->method('uploadPartCopy')
            ->with(self::callback(function () use (&$uploadedParts) {
                ++$uploadedParts;

                return true;
            }))
            ->willReturn(ResultMockFactory::create(UploadPartCopyOutput::class, ['copyPartResult' => new CopyPartResult(['ETag' => 'etag-4711'])]));
        $s3->expects(self::once())->method('completeMultipartUpload')->with(self::callback(function (CompleteMultipartUploadRequest $request) use (&$completedParts) {
            $completedParts = \count($request->getMultipartUpload()->getParts());

            return true;
        }));
        $s3->expects(self::once())
            ->method('headObject')
            ->willReturn(ResultMockFactory::create(HeadObjectOutput::class, ['ContentLength' => 6144 * $megabyte]));

        $s3->copy('bucket', 'robots.txt', 'bucket', 'copy-robots.txt');

        self::assertEquals($completedParts, $uploadedParts);
    }

    private function assertSmallFileUpload(\Closure $callback, string $bucket, string $file, $object): void
    {
        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'putObject', 'completeMultipartUpload'])
            ->getMock();

        $createMultipartUploadCount = 0;
        $s3->expects(self::any())->method('createMultipartUpload')->with(self::callback(function () use (&$createMultipartUploadCount) {
            ++$createMultipartUploadCount;

            return true;
        }))->willReturn(ResultMockFactory::create(CreateMultipartUploadOutput::class, ['UploadId' => '4711']));

        $s3->expects(self::any())->method('abortMultipartUpload')->with(self::callback(function () use (&$createMultipartUploadCount) {
            --$createMultipartUploadCount;

            return true;
        }));
        $s3->expects(self::never())->method('completeMultipartUpload');

        $s3->expects(self::once())->method('putObject')->with(self::callback($callback));
        $s3->upload($bucket, $file, $object);

        self::assertEquals(0, $createMultipartUploadCount, 'We did not abort all uploads properly.');
    }
}
