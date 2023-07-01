<?php

declare(strict_types=1);

namespace AsyncAws\SimpleS3;

use AsyncAws\Core\Stream\FixedSizeStream;
use AsyncAws\Core\Stream\ResultStream;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\CompletedMultipartUpload;
use AsyncAws\S3\ValueObject\CompletedPart;

/**
 * A simplified S3 client that hides some of the complexity of working with S3.
 * The aim of this client is to provide shortcut methods to about 80% common tasks.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class SimpleS3Client extends S3Client
{
    public function getUrl(string $bucket, string $key): string
    {
        $uri = sprintf('/%s/%s', urlencode($bucket), str_replace('%2F', '/', rawurlencode($key)));

        return $this->getEndpoint($uri, [], null);
    }

    public function getPresignedUrl(string $bucket, string $key, ?\DateTimeImmutable $expires = null): string
    {
        $request = new GetObjectRequest([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);

        return $this->presign($request, $expires);
    }

    public function download(string $bucket, string $key): ResultStream
    {
        return $this->getObject(['Bucket' => $bucket, 'Key' => $key])->getBody();
    }

    public function has(string $bucket, string $key): bool
    {
        return $this->objectExists(['Bucket' => $bucket, 'Key' => $key])->isSuccess();
    }

    /**
     * @param string|resource|(callable(int): string)|iterable<string> $object
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   CacheControl?: string,
     *   ContentLength?: int,
     *   ContentType?: string,
     *   Metadata?: array<string, string>,
     *   PartSize?: int,
     * } $options
     */
    public function upload(string $bucket, string $key, $object, array $options = []): void
    {
        $megabyte = 1024 * 1024;
        // Split the stream in 1MB chunk
        $stream = $this->getStream($object, 1 * $megabyte);

        if (!empty($options['ContentLength'])) {
            $contentLength = (int) $options['ContentLength'];
        } else {
            $contentLength = $stream->length();
        }

        /*
         * The maximum number of parts is 10.000. The partSize must be a power of 2.
         * We default this to 64MB per part. That means that we only support to upload
         * files smaller than 64 * 10 000 = 640GB. If you are uploading larger files,
         * please set PartSize to a higher number, like 128, 256 or 512. (Max 4096).
         */
        $partSize = $options['PartSize'] ?? 64;
        unset($options['PartSize']);

        // If file is less than 64MB, use normal upload
        if (null !== $contentLength && $contentLength < 64 * $megabyte) {
            $this->doSmallFileUpload($options, $bucket, $key, $object);

            return;
        }

        $parts = [];
        $uploadId = '';
        $partNumber = 1;
        $chunkIndex = 0;
        $buffer = fopen('php://temp', 'rw+');
        foreach ($stream as $chunk) {
            // Read chunk to resource
            fwrite($buffer, $chunk);
            if (++$chunkIndex < $partSize) {
                // Continue reading chunk into memory
                continue;
            }

            // We have a good chunk of data to upload. If this is the first part, then get uploadId.
            if (1 === $partNumber) {
                /** @var string $uploadId */
                $uploadId = $this->createMultipartUpload(array_merge($options, ['Bucket' => $bucket, 'Key' => $key]))->getUploadId();
            }

            // Start uploading the part.
            $parts[] = $this->doMultipartUpload($bucket, $key, $uploadId, $partNumber, $buffer);
            ++$partNumber;
            $buffer = fopen('php://temp', 'rw+');
            $chunkIndex = 0;
        }

        if ($chunkIndex > 0) {
            if (empty($uploadId)) {
                /*
                 * The first and only part is too small to upload using MultipartUpload.
                 * AWS has a limit of minimum 5MB.
                 *
                 * Lets use a normal upload.
                 */
                $this->doSmallFileUpload($options, $bucket, $key, $buffer);

                return;
            }

            // upload last chunk
            $parts[] = $this->doMultipartUpload($bucket, $key, $uploadId, $partNumber, $buffer);
        }

        if (empty($parts)) {
            // The upload did not contain any data. Let's create an empty file
            $this->doSmallFileUpload($options, $bucket, $key, '');

            return;
        }

        $this->completeMultipartUpload([
            'Bucket' => $bucket,
            'Key' => $key,
            'UploadId' => $uploadId,
            'MultipartUpload' => new CompletedMultipartUpload(['Parts' => $parts]),
        ]);
    }

    /**
     * @param string|resource|(callable(int): string)|iterable<string> $object
     */
    private function getStream($object, int $chunkSize): FixedSizeStream
    {
        return FixedSizeStream::create(
            StreamFactory::create($object, $chunkSize),
            $chunkSize
        );
    }

    /**
     * @param resource $buffer
     */
    private function doMultipartUpload(string $bucket, string $key, string $uploadId, int $partNumber, $buffer): CompletedPart
    {
        try {
            $response = $this->uploadPart([
                'Bucket' => $bucket,
                'Key' => $key,
                'UploadId' => $uploadId,
                'PartNumber' => $partNumber,
                'Body' => $buffer,
            ]);

            return new CompletedPart(['ETag' => $response->getETag(), 'PartNumber' => $partNumber]);
        } catch (\Throwable $e) {
            $this->abortMultipartUpload(['Bucket' => $bucket, 'Key' => $key, 'UploadId' => $uploadId]);

            throw $e;
        }
    }

    /**
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   CacheControl?: string,
     *   ContentLength?: int,
     *   ContentType?: string,
     *   Metadata?: array<string, string>,
     * } $options
     * @param string|resource|(callable(int): string)|iterable<string> $object
     */
    private function doSmallFileUpload(array $options, string $bucket, string $key, $object): void
    {
        $this->putObject(array_merge($options, [
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => $object,
        ]));
    }
}
