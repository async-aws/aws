<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Stream\ResultStream;
use AsyncAws\Flysystem\S3\Visibility\PortableVisibilityConverter;
use AsyncAws\Flysystem\S3\Visibility\VisibilityConverter;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\AwsObject;
use AsyncAws\S3\ValueObject\CommonPrefix;
use AsyncAws\S3\ValueObject\ObjectIdentifier;
use AsyncAws\SimpleS3\SimpleS3Client;
use Generator;
use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemOperationFailed;
use League\Flysystem\PathPrefixer;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\Visibility;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Throwable;

if (!interface_exists(FilesystemAdapter::class)) {
    throw new \LogicException('You cannot use "AsyncAws\Flysystem\S3\S3FilesystemV2" as the "league/flysystem:2.x" package is not installed. Try running "composer require league/flysystem:^2.0".');
}

class S3FilesystemV2 implements FilesystemAdapter
{
    /**
     * @var array
     */
    public const AVAILABLE_OPTIONS = [
        'ACL',
        'CacheControl',
        'ContentDisposition',
        'ContentEncoding',
        'ContentLength',
        'ContentType',
        'Expires',
        'GrantFullControl',
        'GrantRead',
        'GrantReadACP',
        'GrantWriteACP',
        'Metadata',
        'RequestPayer',
        'SSECustomerAlgorithm',
        'SSECustomerKey',
        'SSECustomerKeyMD5',
        'SSEKMSKeyId',
        'ServerSideEncryption',
        'StorageClass',
        'Tagging',
        'WebsiteRedirectLocation',
    ];
    private const EXTRA_METADATA_FIELDS = [
        'Metadata',
        'StorageClass',
        'ETag',
        'VersionId',
    ];

    /**
     * @var S3Client
     */
    private $client;

    /**
     * @var PathPrefixer
     */
    private $prefixer;

    /**
     * @var string
     */
    private $bucket;

    /**
     * @var VisibilityConverter
     */
    private $visibility;

    public function __construct(
        S3Client $client,
        string $bucket,
        string $prefix = '',
        VisibilityConverter $visibility = null
    ) {
        $this->client = $client;
        $this->prefixer = new PathPrefixer($prefix);
        $this->bucket = $bucket;
        $this->visibility = $visibility ?: new PortableVisibilityConverter();
    }

    public function fileExists(string $path): bool
    {
        try {
            $this->client->getObject(
                [
                    'Bucket' => $this->bucket,
                    'Key' => $this->prefixer->prefixPath($path),
                ]
            )->resolve();

            return true;
        } catch (ClientException $e) {
            return false;
        }
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $this->upload($path, $contents, $config);
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->upload($path, $contents, $config);
    }

    public function read(string $path): string
    {
        $body = $this->readObject($path);

        return $body->getContentAsString();
    }

    public function readStream(string $path)
    {
        $body = $this->readObject($path);

        return $body->getContentAsResource();
    }

    public function delete(string $path): void
    {
        $arguments = ['Bucket' => $this->bucket, 'Key' => $this->prefixer->prefixPath($path)];
        $result = $this->client->deleteObject($arguments);

        try {
            $result->resolve();
        } catch (Throwable $exception) {
            throw UnableToDeleteFile::atLocation($path, '', $exception);
        }
    }

    public function deleteDirectory(string $path): void
    {
        $prefix = $this->prefixer->prefixPath($path);
        $prefix = ltrim(rtrim($prefix, '/') . '/', '/');

        $objects = [];
        $params = ['Bucket' => $this->bucket, 'Prefix' => $prefix];
        $result = $this->client->listObjectsV2($params);
        /** @var AwsObject $item */
        foreach ($result->getContents() as $item) {
            $key = $item->getKey();
            if (null !== $key) {
                $objects[] = new ObjectIdentifier(['Key' => $key]);
            }
        }

        if (empty($objects)) {
            return;
        }

        $this->client->deleteObjects(['Bucket' => $this->bucket, 'Delete' => ['Objects' => $objects]])->resolve();
    }

    public function createDirectory(string $path, Config $config): void
    {
        $this->upload(rtrim($path, '/') . '/', '', $config->withDefaults([
            'visibility' => $this->visibility->defaultForDirectories(),
        ]));
    }

    public function setVisibility(string $path, $visibility): void
    {
        $arguments = [
            'Bucket' => $this->bucket,
            'Key' => $this->prefixer->prefixPath($path),
            'ACL' => $this->visibility->visibilityToAcl($visibility),
        ];
        $result = $this->client->putObjectAcl($arguments);

        try {
            $result->resolve();
        } catch (Throwable $exception) {
            throw UnableToSetVisibility::atLocation($path, '', $exception);
        }
    }

    public function visibility(string $path): FileAttributes
    {
        $arguments = ['Bucket' => $this->bucket, 'Key' => $this->prefixer->prefixPath($path)];
        $result = $this->client->getObjectAcl($arguments);

        try {
            $grants = $result->getGrants();
        } catch (Throwable $exception) {
            throw UnableToRetrieveMetadata::visibility($path, '', $exception);
        }

        $visibility = $this->visibility->aclToVisibility($grants);

        return new FileAttributes($path, null, $visibility);
    }

    public function mimeType(string $path): FileAttributes
    {
        return $this->fetchFileMetadata($path, FileAttributes::ATTRIBUTE_MIME_TYPE);
    }

    public function lastModified(string $path): FileAttributes
    {
        return $this->fetchFileMetadata($path, FileAttributes::ATTRIBUTE_LAST_MODIFIED);
    }

    public function fileSize(string $path): FileAttributes
    {
        return $this->fetchFileMetadata($path, FileAttributes::ATTRIBUTE_FILE_SIZE);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $prefix = $this->prefixer->prefixPath($path);
        $options = ['Bucket' => $this->bucket, 'Prefix' => trim($prefix, '/') . '/'];

        if (false === $deep) {
            $options['Delimiter'] = '/';
        }

        $listing = $this->retrievePaginatedListing($options);

        foreach ($listing as $item) {
            yield $this->mapS3ObjectMetadata($item);
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        try {
            $this->copy($source, $destination, $config);
            $this->delete($source);
        } catch (FilesystemOperationFailed $exception) {
            throw UnableToMoveFile::fromLocationTo($source, $destination, $exception);
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        try {
            /** @var string $visibility */
            $visibility = $this->visibility($source)->visibility();
        } catch (Throwable $exception) {
            throw UnableToCopyFile::fromLocationTo($source, $destination, $exception);
        }
        $arguments = [
            'ACL' => $this->visibility->visibilityToAcl($visibility),
            'Bucket' => $this->bucket,
            'Key' => $this->prefixer->prefixPath($destination),
            'CopySource' => rawurlencode($this->bucket . '/' . $this->prefixer->prefixPath($source)),
        ];
        $result = $this->client->copyObject($arguments);

        try {
            $result->resolve();
        } catch (Throwable $exception) {
            throw UnableToCopyFile::fromLocationTo($source, $destination, $exception);
        }
    }

    /**
     * @param string|resource $body
     */
    private function upload(string $path, $body, Config $config): void
    {
        $key = $this->prefixer->prefixPath($path);
        $acl = $this->determineAcl($config);
        $options = $this->createOptionsFromConfig($config);
        $shouldDetermineMimetype = '' !== $body && !\array_key_exists('ContentType', $options);

        if ($shouldDetermineMimetype) {
            $mimeType = (new FinfoMimeTypeDetector())->detectMimeType($path, $body);
            $options['ContentType'] = $mimeType;
        }

        if ($this->client instanceof SimpleS3Client) {
            // Support upload of large files
            $this->client->upload($this->bucket, $key, $body, array_merge($options, ['ACL' => $acl]));
        } else {
            $this->client->putObject(array_merge($options, [
                'Bucket' => $this->bucket,
                'Key' => $key,
                'Body' => $body,
                'ACL' => $acl,
            ]))->resolve();
        }
    }

    private function determineAcl(Config $config): string
    {
        $visibility = (string) $config->get(Config::OPTION_VISIBILITY, Visibility::PRIVATE);

        return $this->visibility->visibilityToAcl($visibility);
    }

    private function createOptionsFromConfig(Config $config): array
    {
        $options = [];

        foreach (static::AVAILABLE_OPTIONS as $option) {
            $value = $config->get($option, '__NOT_SET__');

            if ('__NOT_SET__' !== $value) {
                $options[$option] = $value;
            }
        }

        return $options;
    }

    private function fetchFileMetadata(string $path, string $type): FileAttributes
    {
        $arguments = ['Bucket' => $this->bucket, 'Key' => $this->prefixer->prefixPath($path)];
        $result = $this->client->headObject($arguments);

        try {
            $result->resolve();
        } catch (Throwable $exception) {
            throw UnableToRetrieveMetadata::create($path, $type, '', $exception);
        }

        $attributes = $this->mapS3ObjectMetadata($result, $path);

        if (!$attributes instanceof FileAttributes) {
            throw UnableToRetrieveMetadata::create($path, $type, '');
        }

        return $attributes;
    }

    /**
     * @param HeadObjectOutput|AwsObject|CommonPrefix $item
     */
    private function mapS3ObjectMetadata($item, $path = null): StorageAttributes
    {
        if (null === $path) {
            if ($item instanceof AwsObject) {
                $path = $this->prefixer->stripPrefix($item->getKey() ?? '');
            } elseif ($item instanceof CommonPrefix) {
                $path = $this->prefixer->stripPrefix($item->getPrefix() ?? '');
            } else {
                throw new \RuntimeException(sprintf('Argument 2 of "%s" cannot be null when $item is not instance of "%s" or %s', __METHOD__, AwsObject::class, CommonPrefix::class));
            }
        }

        if ('/' === substr($path, -1)) {
            return new DirectoryAttributes(rtrim($path, '/'));
        }

        $mimeType = null;
        $fileSize = null;
        $lastModified = null;
        $dateTime = null;
        $metadata = [];

        if ($item instanceof AwsObject) {
            $dateTime = $item->getLastModified();
            $fileSize = $item->getSize();
        } elseif ($item instanceof CommonPrefix) {
            // No data available
        } elseif ($item instanceof HeadObjectOutput) {
            $mimeType = $item->getContentType();
            $fileSize = $item->getContentLength();
            $dateTime = $item->getLastModified();
            $metadata = $this->extractExtraMetadata($item);
        } else {
            throw new \RuntimeException(sprintf('Object of class "%s" is not supported in %s()', \get_class($item), __METHOD__));
        }

        if ($dateTime instanceof \DateTimeInterface) {
            $lastModified = $dateTime->getTimestamp();
        }

        return new FileAttributes($path, (int) $fileSize, null, $lastModified, $mimeType, $metadata);
    }

    /**
     * @param HeadObjectOutput $metadata
     */
    private function extractExtraMetadata($metadata): array
    {
        $extracted = [];

        foreach (static::EXTRA_METADATA_FIELDS as $field) {
            $method = 'get' . $field;
            if (!method_exists($metadata, $method)) {
                continue;
            }
            $value = $metadata->$method();
            if (null !== $value) {
                $extracted[$field] = $value;
            }
        }

        return $extracted;
    }

    private function retrievePaginatedListing(array $options): Generator
    {
        $result = $this->client->listObjectsV2($options);

        foreach ($result as $item) {
            yield $item;
        }
    }

    private function readObject(string $path): ResultStream
    {
        $options = ['Bucket' => $this->bucket, 'Key' => $this->prefixer->prefixPath($path)];
        $result = $this->client->getObject($options);

        try {
            return $result->getBody();
        } catch (Throwable $exception) {
            throw UnableToReadFile::fromLocation($path, '', $exception);
        }
    }
}
