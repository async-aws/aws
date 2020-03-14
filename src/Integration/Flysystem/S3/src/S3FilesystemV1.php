<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\S3\Input\ObjectIdentifier;
use AsyncAws\S3\Result\AwsObject;
use AsyncAws\S3\Result\CommonPrefix;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\S3Client;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\CanOverwriteFiles;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use League\Flysystem\Util;

class S3FilesystemV1 extends AbstractAdapter implements CanOverwriteFiles
{
    const PUBLIC_GRANT_URI = 'http://acs.amazonaws.com/groups/global/AllUsers';

    /**
     * @var array
     */
    private static $resultMap = [
        'Body' => 'contents',
        'ContentLength' => 'size',
        'ContentType' => 'mimetype',
        'Size' => 'size',
        'Metadata' => 'metadata',
        'StorageClass' => 'storageclass',
        'ETag' => 'etag',
        'VersionId' => 'versionid',
    ];

    /**
     * @var array
     */
    private static $metaOptions = [
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

    /**
     * @var S3Client
     */
    private $client;

    /**
     * @var string
     */
    private $bucket;

    /**
     * @var array
     */
    private $options = [];

    public function __construct(S3Client $client, string $bucket, string $prefix = '', array $options = [])
    {
        $this->client = $client;
        $this->bucket = $bucket;
        $this->setPathPrefix($prefix);
        $this->options = $options;
    }

    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     *
     * @return false|array false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
    {
        return $this->upload($path, $contents, $config);
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     *
     * @return false|array false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        return $this->upload($path, $contents, $config);
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function rename($path, $newpath)
    {
        if (!$this->copy($path, $newpath)) {
            return false;
        }

        return $this->delete($path);
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
        $location = $this->applyPathPrefix($path);

        $result = $this->client->deleteObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $location,
            ]
        );

        $result->resolve();

        return !$this->has($path);
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($dirname)
    {
        $prefix = $this->applyPathPrefix($dirname) . '/';

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
            return true;
        }

        $this->client->deleteObjects(['Bucket' => $this->bucket, 'Delete' => ['Objects' => $objects]])->resolve();

        return true;
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     *
     * @return bool|array
     */
    public function createDir($dirname, Config $config)
    {
        return $this->upload($dirname . '/', '', $config);
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function has($path)
    {
        $location = $this->applyPathPrefix($path);

        $result = $this->client->getObject(array_merge($this->options, [
            'Bucket' => $this->bucket,
            'Key' => $location,
        ]));

        try {
            $result->resolve();

            return true;
        } catch (ClientException $e) {
            if (404 !== $e->getResponse()->getStatusCode()) {
                throw $e;
            }
        }

        return $this->doesDirectoryExist($location);
    }

    /**
     * Read a file.
     *
     * @param string $path
     *
     * @return false|array
     */
    public function read($path)
    {
        $response = $this->readObject($path);

        if (false !== $response) {
            $response['contents'] = $response['contents']->getContentAsString();
        }

        return $response;
    }

    /**
     * List contents of a directory.
     *
     * @param string $directory
     * @param bool   $recursive
     *
     * @return array
     */
    public function listContents($directory = '', $recursive = false)
    {
        $prefix = $this->applyPathPrefix(rtrim($directory, '/') . '/');
        $options = ['Bucket' => $this->bucket, 'Prefix' => ltrim($prefix, '/')];

        if (false === $recursive) {
            $options['Delimiter'] = '/';
        }

        $listing = $this->retrievePaginatedListing($options);
        $normalizer = [$this, 'normalizeResponse'];
        $normalized = array_map($normalizer, $listing);

        return Util::emulateDirectories($normalized);
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return false|array
     */
    public function getMetadata($path)
    {
        $result = $this->client->headObject([
            'Bucket' => $this->bucket,
            'Key' => $this->applyPathPrefix($path),
        ] + $this->options
        );

        try {
            $result->resolve();
        } catch (ClientException $exception) {
            if (404 === $exception->getResponse()->getStatusCode()) {
                return false;
            }

            throw $exception;
        }

        return $this->normalizeResponse($result, $path);
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return false|int
     */
    public function getSize($path)
    {
        $metadata = $this->getMetadata($path);
        if (!isset($metadata['size'])) {
            return false;
        }

        return (int) $metadata['size'];
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     *
     * @return false|string
     */
    public function getMimetype($path)
    {
        $metadata = $this->getMetadata($path);
        if (!isset($metadata['mimetype'])) {
            return false;
        }

        return $metadata['mimetype'];
    }

    /**
     * Get the timestamp of a file.
     *
     * @param string $path
     *
     * @return false|int
     */
    public function getTimestamp($path)
    {
        $metadata = $this->getMetadata($path);
        if (!isset($metadata['timestamp'])) {
            return false;
        }

        return $metadata['timestamp'];
    }

    /**
     * Write a new file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
        return $this->upload($path, $resource, $config);
    }

    /**
     * Update a file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function updateStream($path, $resource, Config $config)
    {
        return $this->upload($path, $resource, $config);
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($path, $newpath)
    {
        $result = $this->client->copyObject([
            'Bucket' => $this->bucket,
            'Key' => $this->applyPathPrefix($newpath),
            'CopySource' => rawurlencode('/' . $this->applyPathPrefix($path)),
            'ACL' => AdapterInterface::VISIBILITY_PUBLIC === $this->getRawVisibility($path) ? 'public-read' : 'private',
        ] + $this->options
        );

        try {
            $result->resolve();
        } catch (ClientException $e) {
            return false;
        }

        return true;
    }

    /**
     * Read a file as a stream.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function readStream($path)
    {
        $response = $this->readObject($path);

        if (false !== $response) {
            $response['stream'] = $response['contents']->getContentAsResource();
            unset($response['contents']);
        }

        return $response;
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
    {
        $result = $this->client->putObjectAcl([
            'Bucket' => $this->bucket,
            'Key' => $this->applyPathPrefix($path),
            'ACL' => AdapterInterface::VISIBILITY_PUBLIC === $visibility ? 'public-read' : 'private',
        ]);

        try {
            $result->resolve();
        } catch (ClientException $exception) {
            return false;
        }

        return compact('path', 'visibility');
    }

    /**
     * Get the visibility of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getVisibility($path)
    {
        $rawVisibility = $this->getRawVisibility($path);

        return ['visibility' => $rawVisibility];
    }

    /**
     * {@inheritdoc}
     */
    public function applyPathPrefix($path)
    {
        return ltrim(parent::applyPathPrefix($path), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function setPathPrefix($prefix): void
    {
        $prefix = ltrim($prefix, '/');

        parent::setPathPrefix($prefix);
    }

    /**
     * @return array
     */
    protected function retrievePaginatedListing(array $options)
    {
        $result = $this->client->listObjectsV2($options);
        $listing = [];

        /** @var ListObjectsV2Output $single */
        foreach ($result->getIterator() as $single) {
            $listing = array_merge($listing, $single->getContents(), $single->getCommonPrefixes());
        }

        return $listing;
    }

    /**
     * Read an object and normalize the response.
     *
     * @param string $path
     *
     * @return array|bool
     */
    protected function readObject($path)
    {
        $options = [
            'Bucket' => $this->bucket,
            'Key' => $this->applyPathPrefix($path),
        ];

        if (isset($this->options['@http'])) {
            $options['@http'] = $this->options['@http'];
        }

        $result = $this->client->getObject($options + $this->options);

        try {
            $result->resolve();
        } catch (ClientException $e) {
            return false;
        }

        return $this->normalizeResponse($result, $path);
    }

    /**
     * Get the object acl presented as a visibility.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getRawVisibility($path)
    {
        $result = $this->client->getObjectAcl([
            'Bucket' => $this->bucket,
            'Key' => $this->applyPathPrefix($path),
        ]);

        $result->resolve();
        $visibility = AdapterInterface::VISIBILITY_PRIVATE;

        foreach ($result->getGrants() as $grant) {
            if (null === $grantee = $grant->getGrantee()) {
                continue;
            }

            if (
                null !== $grantee->getURI()
                && self::PUBLIC_GRANT_URI === $grantee->getURI()
                && 'READ' === $grant->getPermission()
            ) {
                $visibility = AdapterInterface::VISIBILITY_PUBLIC;

                break;
            }
        }

        return $visibility;
    }

    /**
     * Upload an object.
     *
     * @param string          $path
     * @param string|resource $body
     *
     * @return array|bool
     */
    protected function upload($path, $body, Config $config)
    {
        $key = $this->applyPathPrefix($path);
        $options = $this->getOptionsFromConfig($config);
        $acl = \array_key_exists('ACL', $options) ? $options['ACL'] : 'private';

        if (!$this->isOnlyDir($path)) {
            if (!isset($options['ContentType'])) {
                $options['ContentType'] = Util::guessMimeType($path, $body);
            }

            if (!isset($options['ContentLength'])) {
                $options['ContentLength'] = \is_resource($body) ? Util::getStreamSize($body) : Util::contentSize($body);
            }

            if (null === $options['ContentLength']) {
                unset($options['ContentLength']);
            }
        }

        $result = $this->client->putObject(array_merge($options, [
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Body' => $body,
            'ACL' => $acl,
        ]));

        try {
            $result->resolve();
        } catch (ClientException $e) {
            return false;
        }

        return $this->normalizeResponse($result, $path);
    }

    /**
     * Get options from the config.
     *
     * @return array
     */
    protected function getOptionsFromConfig(Config $config)
    {
        $options = $this->options;

        if ($visibility = $config->get('visibility')) {
            // For local reference
            $options['visibility'] = $visibility;
            // For external reference
            $options['ACL'] = AdapterInterface::VISIBILITY_PUBLIC === $visibility ? 'public-read' : 'private';
        }

        if ($mimetype = $config->get('mimetype')) {
            // For local reference
            $options['mimetype'] = $mimetype;
            // For external reference
            $options['ContentType'] = $mimetype;
        }

        foreach (static::$metaOptions as $option) {
            if (!$config->has($option)) {
                continue;
            }
            $options[$option] = $config->get($option);
        }

        return $options;
    }

    /**
     * Normalize the object result array.
     *
     * @param AwsObject|CommonPrefix|HeadObjectOutput|GetObjectOutput|PutObjectOutput $output
     */
    protected function normalizeResponse(object $output, ?string $path = null): array
    {
        $result = [
            'path' => $path ?: $this->removePathPrefix(
                method_exists($output, 'getKey') ? $output->getKey() : (method_exists($output, 'getPrefix') ? $output->getPrefix() : null)
            ),
        ];
        $result = array_merge($result, Util::pathinfo($result['path']));

        if (method_exists($output, 'getLastModified')) {
            $dateTime = $output->getLastModified();
            if (null !== $dateTime) {
                $result['timestamp'] = $dateTime->getTimestamp();
            }
        }

        if ($this->isOnlyDir($result['path'])) {
            $result['type'] = 'dir';
            $result['path'] = rtrim($result['path'], '/');

            return $result;
        }

        return array_merge($result, $this->resultMap($output, static::$resultMap), ['type' => 'file']);
    }

    protected function doesDirectoryExist(string $location): bool
    {
        // Maybe this isn't an actual key, but a prefix.
        // Do a prefix listing of objects to determine.
        $result = $this->client->listObjectsV2([
            'Bucket' => $this->bucket,
            'Prefix' => rtrim($location, '/') . '/',
            'MaxKeys' => 1,
        ]
        );

        try {
            $result->resolve();

            return !empty($result->getContents()) || !empty($result->getCommonPrefixes());
        } catch (ClientException $e) {
            if (403 === $e->getResponse()->getStatusCode()) {
                return false;
            }

            throw $e;
        }
    }

    /**
     * Check if the path contains only directories.
     *
     * @param string $path
     *
     * @return bool
     */
    private function isOnlyDir($path)
    {
        return '/' === substr($path, -1);
    }

    private function resultMap(object $object, array $map)
    {
        $result = [];
        foreach ($map as $from => $to) {
            $methodName = 'get' . $from;
            if (method_exists($object, $methodName)) {
                $result[$to] = \call_user_func([$object, $methodName]);
            }
        }

        return $result;
    }
}
