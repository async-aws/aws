<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Filesystem;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider as AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider
{
    /**
     * @var AsyncAwsFilesystemManager|null
     */
    private $manager;

    public function boot()
    {
        /** @var FilesystemManager $manager */
        $manager = $this->app['filesystem'];

        $manager->extend('async-aws-s3', \Closure::fromCallable([$this, 'createFilesystem']));
    }

    public function createFilesystem($app, array $config): FilesystemAdapter
    {
        return $this->getManager($app)->createAsyncAwsS3Driver($config);
    }

    private function getManager($app): AsyncAwsFilesystemManager
    {
        if (null === $this->manager) {
            $this->manager = new AsyncAwsFilesystemManager($app);
        }

        return $this->manager;
    }
}
