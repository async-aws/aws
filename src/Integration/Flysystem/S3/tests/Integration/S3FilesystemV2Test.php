<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Tests\Integration;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Flysystem\S3\S3FilesystemV2;
use AsyncAws\S3\S3Client;
use League\Flysystem\AdapterTestUtilities\FilesystemAdapterTestCase;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Visibility;

if (!\class_exists(FilesystemAdapterTestCase::class)) {
    \class_alias(\League\Flysystem\FilesystemAdapterTestCase::class, FilesystemAdapterTestCase::class);
}

class S3FilesystemV2Test extends FilesystemAdapterTestCase
{
    private static $docker = false;

    /**
     * @var string
     */
    private static $adapterPrefix = 'test-prefix';

    /**
     * @var S3Client
     */
    private static $s3Client;

    public static function setUpBeforeClass(): void
    {
        if (!interface_exists(FilesystemAdapter::class)) {
            self::markTestSkipped('Flysystem v2 is not installed');
        }

        static::$adapterPrefix = 'ci/' . bin2hex(random_bytes(10));
    }

    public function testWriting_with_a_specific_mime_type()
    {
        $adapter = $this->adapter();
        $adapter->write('some/path.txt', 'contents', new Config(['ContentType' => 'text/plain+special']));
        $mimeType = $adapter->mimeType('some/path.txt')->mimeType();
        self::assertEquals('text/plain+special', $mimeType);
    }

    public function testListing_contents_recursive(): void
    {
        if (\PHP_VERSION_ID === 70406) {
            self::markTestSkipped('Skipped because of https://bugs.php.net/bug.php?id=79616');
        }

        $adapter = $this->adapter();
        $adapter->write('something/0/here.txt', 'contents', new Config());
        $adapter->write('something/1/also/here.txt', 'contents', new Config());

        $contents = iterator_to_array($adapter->listContents('', true));

        self::assertCount(2, $contents);
        self::assertContainsOnlyInstancesOf(FileAttributes::class, $contents);
        /** @var FileAttributes $file */
        $file = $contents[0];
        self::assertEquals('something/0/here.txt', $file->path());
        /** @var FileAttributes $file */
        $file = $contents[1];
        self::assertEquals('something/1/also/here.txt', $file->path());
    }

    /**
     * @test
     */
    public function listing_contents_shallow(): void
    {
        if (\PHP_VERSION_ID === 70406) {
            self::markTestSkipped('Skipped because of https://bugs.php.net/bug.php?id=79616');
        }

        parent::listing_contents_shallow();
    }

    /**
     * @test
     */
    public function listing_contents_recursive(): void
    {
        if (\PHP_VERSION_ID === 70406) {
            self::markTestSkipped('Skipped because of https://bugs.php.net/bug.php?id=79616');
        }

        parent::listing_contents_recursive();
    }

    /**
     * @test
     */
    public function copying_a_file(): void
    {
        if (!self::$docker) {
            // canned ACL is not supported in fake-s3: https://github.com/jubos/fake-s3/issues/104
            parent::copying_a_file();

            return;
        }

        $adapter = $this->adapter();
        $adapter->write(
            'source.txt',
            'contents to be copied',
            new Config([Config::OPTION_VISIBILITY => Visibility::PUBLIC])
        );

        $adapter->copy('source.txt', 'destination.txt', new Config());

        self::assertTrue($adapter->fileExists('source.txt'));
        self::assertTrue($adapter->fileExists('destination.txt'));
        self::assertEquals('contents to be copied', $adapter->read('destination.txt'));
    }

    /**
     * @test
     */
    public function copying_a_file_with_collision(): void
    {
        if (self::$docker) {
            self::markTestSkipped(sprintf('Test "%s" will always fail when using docker image "asyncaws/testing-s3"', __FUNCTION__));
        }

        parent::copying_a_file_with_collision();
    }

    /**
     * @test
     */
    public function moving_a_file(): void
    {
        if (!self::$docker) {
            // canned ACL is not supported in fake-s3: https://github.com/jubos/fake-s3/issues/104
            parent::moving_a_file();

            return;
        }

        $adapter = $this->adapter();
        $adapter->write(
            'source.txt',
            'contents to be copied',
            new Config([Config::OPTION_VISIBILITY => Visibility::PUBLIC])
        );
        $adapter->move('source.txt', 'destination.txt', new Config());
        self::assertFalse($adapter->fileExists('source.txt'), 'After moving a file should no longer exist in the original location.');
        self::assertTrue($adapter->fileExists('destination.txt'), 'After moving, a file should be present at the new location.');
        self::assertEquals('contents to be copied', $adapter->read('destination.txt'));
    }

    /**
     * @test
     */
    public function setting_visibility(): void
    {
        if (self::$docker) {
            self::markTestSkipped(sprintf('Test "%s" will always fail when using docker image "asyncaws/testing-s3"', __FUNCTION__));
        }

        parent::setting_visibility();
    }

    /**
     * @test
     */
    public function fetching_visibility_of_non_existing_file(): void
    {
        if (self::$docker) {
            self::markTestSkipped(sprintf('Test "%s" will always fail when using docker image "asyncaws/testing-s3"', __FUNCTION__));
        }

        parent::fetching_visibility_of_non_existing_file();
    }

    /**
     * @test
     */
    public function setting_visibility_on_a_file_that_does_not_exist(): void
    {
        if (self::$docker) {
            self::markTestSkipped(sprintf('Test "%s" will always fail when using docker image "asyncaws/testing-s3"', __FUNCTION__));
        }

        parent::setting_visibility_on_a_file_that_does_not_exist();
    }

    /**
     * @test
     */
    public function creating_a_directory(): void
    {
        if (self::$docker) {
            self::markTestSkipped(sprintf('Test "%s" will always fail when using docker image "asyncaws/testing-s3"', __FUNCTION__));
        }

        parent::creating_a_directory();
    }

    /**
     * @test
     */
    public function fetching_the_mime_type_of_an_svg_file(): void
    {
        self::markTestSkipped(sprintf('Test "%s" will always fail because test resources are not available.', __FUNCTION__));
    }

    protected static function createFilesystemAdapter(): FilesystemAdapter
    {
        $bucket = isset($_SERVER['FLYSYSTEM_AWS_S3_BUCKET']) ? $_SERVER['FLYSYSTEM_AWS_S3_BUCKET'] : 'flysystem-test-bucket';
        $prefix = isset($_SERVER['FLYSYSTEM_AWS_S3_PREFIX']) ? $_SERVER['FLYSYSTEM_AWS_S3_PREFIX'] : static::$adapterPrefix;

        return new S3FilesystemV2(self::s3Client(), $bucket, $prefix);
    }

    private static function s3Client(): S3Client
    {
        if (self::$s3Client instanceof S3Client) {
            return self::$s3Client;
        }

        $key = $_SERVER['FLYSYSTEM_AWS_S3_KEY'] ?? null;
        $secret = $_SERVER['FLYSYSTEM_AWS_S3_SECRET'] ?? null;
        $bucket = $_SERVER['FLYSYSTEM_AWS_S3_BUCKET'] ?? null;
        $region = isset($_SERVER['FLYSYSTEM_AWS_S3_REGION']) ? $_SERVER['FLYSYSTEM_AWS_S3_REGION'] : 'eu-central-1';

        if (!$key || !$secret || !$bucket) {
            self::$docker = true;
            $options = ['endpoint' => 'http://localhost:4570'];
            if (\is_callable([Configuration::class, 'optionExists']) && Configuration::optionExists('pathStyleEndpoint')) {
                $options += ['pathStyleEndpoint' => true];
            }

            return self::$s3Client = new S3Client($options, new NullProvider());
        }

        $options = ['accessKeyId' => $key, 'accessKeySecret' => $secret, 'region' => $region];
        if (\is_callable([Configuration::class, 'optionExists']) && Configuration::optionExists('pathStyleEndpoint')) {
            $options += ['pathStyleEndpoint' => true];
        }

        return self::$s3Client = new S3Client($options);
    }
}
