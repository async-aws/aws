<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Queue;

use AsyncAws\Illuminate\Queue\Connector\AsyncAwsSqsConnector;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider as AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider
{
    public function boot()
    {
        /** @var QueueManager $manager */
        $manager = $this->app['queue'];

        $manager->addConnector('async-aws-sqs', \Closure::fromCallable([$this, 'createConnector']));
    }

    public function createConnector(): AsyncAwsSqsConnector
    {
        return new AsyncAwsSqsConnector();
    }
}
