<?php

declare(strict_types=1);

namespace AsyncAws\SimpleS3\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use AsyncAws\S3\Result\CreateMultipartUploadOutput;
use AsyncAws\S3\Result\UploadPartOutput;
use AsyncAws\SimpleS3\SimpleS3Client;
use PHPUnit\Framework\Attributes\DataProvider;
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

        $this->assertSmallFileUpload($callback, $bucket, $file, $object);
    }

    #[DataProvider('provideConditionalWriteOptions')]
    public function testUploadSmallFileForwardsConditionalWriteOption(string $option, string $value): void
    {
        $callback = static function (array $input) use ($option, $value): bool {
            self::assertSame($value, $input[$option]);

            return true;
        };

        $this->assertSmallFileUpload(
            $callback,
            'bucket',
            'conditional.txt',
            'contents',
            [$option => $value]
        );
    }

    public static function provideConditionalWriteOptions(): iterable
    {
        yield 'If-Match' => ['IfMatch', '"expected-etag"'];
        yield 'If-None-Match' => ['IfNoneMatch', '*'];
    }

    #[DataProvider('provideConditionalWriteOptions')]
    public function testMultipartUploadAppliesConditionalWriteOptionOnlyOnCompletion(string $option, string $value): void
    {
        $bucket = 'bucket';
        $file = 'conditional.txt';
        $object = fopen('php://temp', 'rw+');
        fwrite($object, str_repeat('a', 2 * 1024 * 1024));

        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'putObject', 'completeMultipartUpload', 'uploadPart'])
            ->getMock();

        $s3->expects(self::never())->method('abortMultipartUpload');
        $s3->expects(self::never())->method('putObject');
        $s3->expects(self::once())
            ->method('createMultipartUpload')
            ->with(self::callback(static function (array $input) use ($bucket, $file, $option): bool {
                self::assertSame($bucket, $input['Bucket']);
                self::assertSame($file, $input['Key']);
                self::assertSame(['purpose' => 'conditional-write'], $input['Metadata']);
                self::assertArrayNotHasKey($option, $input);

                return true;
            }))
            ->willReturnCallback(static function (): CreateMultipartUploadOutput {
                $upload = self::createStub(CreateMultipartUploadOutput::class);
                $upload->method('getUploadId')->willReturn('conditional-upload-id');

                return $upload;
            });
        $s3->expects(self::exactly(2))
            ->method('uploadPart')
            ->willReturnCallback(static function (array $part) use ($option): UploadPartOutput {
                self::assertArrayNotHasKey($option, $part);

                $output = self::createStub(UploadPartOutput::class);
                $output->method('getETag')->willReturn("etag-{$part['PartNumber']}");

                return $output;
            });
        $s3->expects(self::once())
            ->method('completeMultipartUpload')
            ->with(self::callback(static function (array $input) use ($option, $value): bool {
                self::assertSame($value, $input[$option]);
                self::assertSame('conditional-upload-id', $input['UploadId']);
                self::assertCount(2, $input['MultipartUpload']->getParts());

                return true;
            }))
            ->willReturn(self::createStub(CompleteMultipartUploadOutput::class));

        $s3->upload($bucket, $file, $object, [
            'PartSize' => 1,
            'Metadata' => ['purpose' => 'conditional-write'],
            $option => $value,
        ]);
    }

    public function testMultipartUploadIsNotAbortedWhenCompletionFails(): void
    {
        $object = fopen('php://temp', 'rw+');
        fwrite($object, str_repeat('a', 2 * 1024 * 1024));
        $failure = new \RuntimeException('Conditional request failed.');

        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'putObject', 'completeMultipartUpload', 'uploadPart'])
            ->getMock();

        $s3->expects(self::never())->method('putObject');
        $s3->expects(self::once())
            ->method('createMultipartUpload')
            ->willReturnCallback(static function (): CreateMultipartUploadOutput {
                $upload = self::createStub(CreateMultipartUploadOutput::class);
                $upload->method('getUploadId')->willReturn('failed-upload-id');

                return $upload;
            });
        $s3->expects(self::exactly(2))
            ->method('uploadPart')
            ->willReturnCallback(static function (array $part): UploadPartOutput {
                $output = self::createStub(UploadPartOutput::class);
                $output->method('getETag')->willReturn("etag-{$part['PartNumber']}");

                return $output;
            });
        $s3->expects(self::once())
            ->method('completeMultipartUpload')
            ->willThrowException($failure);
        $s3->expects(self::never())->method('abortMultipartUpload');

        $this->expectExceptionObject($failure);

        $s3->upload('bucket', 'conditional.txt', $object, [
            'PartSize' => 1,
            'IfNoneMatch' => '*',
        ]);
    }

    #[DataProvider('provideConditionalWriteOptionNames')]
    public function testMultipartUploadIgnoresNullConditionalWriteOption(string $option): void
    {
        $object = fopen('php://temp', 'rw+');
        fwrite($object, str_repeat('a', 2 * 1024 * 1024));

        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'completeMultipartUpload', 'uploadPart'])
            ->getMock();

        $s3->expects(self::once())
            ->method('createMultipartUpload')
            ->with(self::callback(static function (array $input) use ($option): bool {
                self::assertArrayNotHasKey($option, $input);

                return true;
            }))
            ->willReturnCallback(static function (): CreateMultipartUploadOutput {
                $upload = self::createStub(CreateMultipartUploadOutput::class);
                $upload->method('getUploadId')->willReturn('null-condition-upload-id');

                return $upload;
            });
        $s3->expects(self::exactly(2))
            ->method('uploadPart')
            ->willReturnCallback(static function (array $part): UploadPartOutput {
                $output = self::createStub(UploadPartOutput::class);
                $output->method('getETag')->willReturn("etag-{$part['PartNumber']}");

                return $output;
            });
        $s3->expects(self::once())
            ->method('completeMultipartUpload')
            ->with(self::callback(static function (array $input) use ($option): bool {
                self::assertArrayNotHasKey($option, $input);

                return true;
            }))
            ->willReturn(self::createStub(CompleteMultipartUploadOutput::class));

        $s3->upload('bucket', 'conditional.txt', $object, [
            'PartSize' => 1,
            $option => null,
        ]);
    }

    public static function provideConditionalWriteOptionNames(): iterable
    {
        yield 'If-Match' => ['IfMatch'];
        yield 'If-None-Match' => ['IfNoneMatch'];
    }

    #[DataProvider('provideConditionalWriteOptions')]
    public function testUnknownLengthSmallUploadForwardsConditionalWriteOption(string $option, string $value): void
    {
        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'contents');
        fseek($resource, 0, \SEEK_SET);

        $callback = static function (array $input) use ($option, $value): bool {
            self::assertSame($value, $input[$option]);

            return true;
        };

        $this->assertSmallFileUpload(
            $callback,
            'bucket',
            'conditional.txt',
            static function (int $length) use ($resource): string {
                return fread($resource, $length);
            },
            ['PartSize' => 2, $option => $value]
        );
    }

    #[DataProvider('provideConditionalWriteOptions')]
    public function testUnknownLengthEmptyUploadForwardsConditionalWriteOption(string $option, string $value): void
    {
        $callback = static function (array $input) use ($option, $value): bool {
            self::assertSame($value, $input[$option]);

            return true;
        };

        $this->assertSmallFileUpload(
            $callback,
            'bucket',
            'conditional.txt',
            static function (int $length): string {
                return '';
            },
            ['PartSize' => 2, $option => $value]
        );
    }

    #[DataProvider('providePartSizes')]
    public function testUploadIsSmallerThanPartSize(int $partSize)
    {
        $object = fopen('php://temp', 'rw+');
        fwrite($object, str_repeat('a', $partSize * 1024 * 512));

        $bucket = 'bucket';
        $file = 'some-file.txt';
        $callback = function (array $input) use ($bucket, $file, $object) {
            self::assertEquals($bucket, $input['Bucket']);
            self::assertEquals($file, $input['Key']);
            self::assertEquals($object, $input['Body']);

            return true;
        };

        $this->assertSmallFileUpload($callback, $bucket, $file, $object, ['PartSize' => $partSize]);
    }

    #[DataProvider('providePartSizes')]
    public function testUploadMatchesPartSize(int $partSize)
    {
        $object = fopen('php://temp', 'rw+');
        fwrite($object, str_repeat('a', $partSize * 1024 * 1024));

        $bucket = 'bucket';
        $file = 'some-file.txt';
        $callback = function (array $input) use ($bucket, $file, $object) {
            self::assertEquals($bucket, $input['Bucket']);
            self::assertEquals($file, $input['Key']);
            self::assertEquals($object, $input['Body']);

            return true;
        };

        $this->assertSmallFileUpload($callback, $bucket, $file, $object, ['PartSize' => $partSize]);
    }

    #[DataProvider('providePartSizes')]
    public function testUploadExceedsPartSize(int $partSize)
    {
        $object = fopen('php://temp', 'rw+');
        fwrite($object, str_repeat('a', $partSize * 4096 * 1024));

        $bucket = 'bucket';
        $file = 'some-file.txt';

        $this->assertMultipartFileUpload(4, $bucket, $file, $object, ['PartSize' => $partSize]);
    }

    public static function providePartSizes(): \Generator
    {
        foreach ([1, 16, 64] as $partSizeInMiB) {
            yield "$partSizeInMiB MiB" => [$partSizeInMiB];
        }
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

        $this->assertSmallFileUpload($callback, $bucket, $file, $object);
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

        $this->assertSmallFileUpload($callback, $bucket, $file, static function (int $length) use ($resource): string {
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

        $this->assertSmallFileUpload($callback, $bucket, $file, (static function () use ($contents): iterable {
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

    private function assertSmallFileUpload(\Closure $callback, string $bucket, string $file, $object, array $options = []): void
    {
        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'putObject', 'completeMultipartUpload', 'uploadPart'])
            ->getMock();

        $s3->expects(self::never())->method('createMultipartUpload');
        $s3->expects(self::never())->method('abortMultipartUpload');
        $s3->expects(self::never())->method('completeMultipartUpload');
        $s3->expects(self::never())->method('uploadPart');

        $s3->expects(self::once())->method('putObject')->with(self::callback($callback));
        $s3->upload($bucket, $file, $object, $options);
    }

    private function assertMultipartFileUpload(int $expectedNumberOfParts, string $bucket, string $file, $object, array $options = []): void
    {
        $s3 = $this->getMockBuilder(SimpleS3Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createMultipartUpload', 'abortMultipartUpload', 'putObject', 'completeMultipartUpload', 'uploadPart'])
            ->getMock();

        $s3->expects(self::never())->method('abortMultipartUpload');
        $s3->expects(self::never())->method('putObject');

        $s3->expects(self::once())->method('createMultipartUpload')->willReturnCallback(static function (array $input) use ($bucket, $file): CreateMultipartUploadOutput {
            self::assertSame($bucket, $input['Bucket']);
            self::assertSame($file, $input['Key']);

            $upload = self::createStub(CreateMultipartUploadOutput::class);
            $upload->method('getUploadId')->willReturn('some-upload-id');

            return $upload;
        });
        $s3->expects(self::exactly($expectedNumberOfParts))->method('uploadPart')->willReturnCallback(static function (array $part) use ($bucket, $file): UploadPartOutput {
            self::assertSame($bucket, $part['Bucket']);
            self::assertSame($file, $part['Key']);
            self::assertSame('some-upload-id', $part['UploadId']);
            self::assertIsInt($part['PartNumber']);
            self::assertGreaterThan(0, $part['PartNumber']);

            $output = self::createStub(UploadPartOutput::class);
            $output->method('getETag')->willReturn("some-etag-{$part['PartNumber']}");

            return $output;
        });
        $s3->expects(self::once())->method('completeMultipartUpload')->willReturnCallback(static function (array $input) use ($bucket, $file, $expectedNumberOfParts): CompleteMultipartUploadOutput {
            self::assertSame($bucket, $input['Bucket']);
            self::assertSame($file, $input['Key']);
            self::assertSame('some-upload-id', $input['UploadId']);
            self::assertCount($expectedNumberOfParts, $input['MultipartUpload']->getParts());

            return self::createStub(CompleteMultipartUploadOutput::class);
        });

        $s3->upload($bucket, $file, $object, $options);
    }
}
