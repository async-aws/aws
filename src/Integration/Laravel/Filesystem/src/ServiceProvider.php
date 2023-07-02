<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Filesystem;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider as AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider
{
    /**
     * @var AsyncAwsFilesystemManager|null
     */
    private $manager;

    /**
     * @return void
     */
    public function boot()
    {
        /** @var FilesystemManager $manager */
        $manager = $this->app['filesystem'];

        $manager->extend('async-aws-s3', \Closure::fromCallable([$this, 'createFilesystem']));
    }

    /**
     * @param Application $app
     * @param array{
     *     key?: string|null,
     *     secret?: string|null,
     *     token?: string|null,
     *     endpoint?: string|null,
     *     region?: string|null,
     *     bucket: string,
     *     root?: string|null,
     *     options?: array<string, mixed>,
     *     ...
     * } $config
     */
    public function createFilesystem($app, array $config): FilesystemAdapter
    {
        return $this->getManager($app)->createAsyncAwsS3Driver($config);
    }

    /**
     * @param Application $app
     */
    private function getManager($app): AsyncAwsFilesystemManager
    {
        if (null === $this->manager) {
            $this->manager = new AsyncAwsFilesystemManager($app);
        }

        return $this->manager;
    }
}
