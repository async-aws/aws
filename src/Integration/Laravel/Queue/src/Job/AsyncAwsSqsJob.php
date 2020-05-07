<?php

namespace AsyncAws\Illuminate\Queue\Job;

use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\ValueObject\Message;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;

/**
 * This class is a port from Illuminate\Queue\Jobs\SqsJob.
 */
class AsyncAwsSqsJob extends Job implements JobContract
{
    /**
     * The Amazon SQS client instance.
     *
     * @var SqsClient
     */
    protected $sqs;

    /**
     * The Amazon SQS job instance.
     *
     * @var Message
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param string $connectionName
     * @param string $queue
     *
     * @return void
     */
    public function __construct(Container $container, SqsClient $sqs, Message $job, $connectionName, $queue)
    {
        $this->sqs = $sqs;
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
        $this->connectionName = $connectionName;
    }

    /**
     * Release the job back into the queue.
     *
     * @param int $delay
     *
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);

        $this->sqs->changeMessageVisibility([
            'QueueUrl' => $this->queue,
            'ReceiptHandle' => $this->job->getReceiptHandle(),
            'VisibilityTimeout' => $delay,
        ]);
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();

        $this->sqs->deleteMessage([
            'QueueUrl' => $this->queue, 'ReceiptHandle' => $this->job->getReceiptHandle(),
        ]);
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return (int) $this->job->getAttributes()['ApproximateReceiveCount'];
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return (string) $this->job->getMessageId();
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return (string) $this->job->getBody();
    }

    /**
     * Get the underlying SQS client instance.
     *
     * @return \AsyncAws\Sqs\SqsClient
     */
    public function getSqs()
    {
        return $this->sqs;
    }

    /**
     * Get the underlying raw SQS job.
     *
     * @return array
     */
    public function getSqsJob()
    {
        return [
            'Attributes' => $this->job->getAttributes(),
            'Body' => $this->job->getBody(),
            'MessageId' => $this->job->getMessageId(),
            'ReceiptHandle' => $this->job->getReceiptHandle(),
        ];
    }
}
