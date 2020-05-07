<?php

namespace AsyncAws\Illuminate\Queue;

use AsyncAws\Illuminate\Queue\Job\AsyncAwsSqsJob;
use AsyncAws\Sqs\Enum\QueueAttributeName;
use AsyncAws\Sqs\SqsClient;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use Illuminate\Support\Str;

/**
 * This class is a port from Illuminate\Queue\SqsQueue.
 */
class AsyncAwsSqsQueue extends Queue implements QueueContract
{
    /**
     * The Amazon SQS instance.
     *
     * @var SqsClient
     */
    protected $sqs;

    /**
     * The name of the default queue.
     *
     * @var string
     */
    protected $default;

    /**
     * The queue URL prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The queue name suffix.
     *
     * @var string
     */
    private $suffix;

    /**
     * Create a new Amazon SQS queue instance.
     *
     * @param string $default
     * @param string $prefix
     * @param string $suffix
     *
     * @return void
     */
    public function __construct(SqsClient $sqs, $default, $prefix = '', $suffix = '')
    {
        $this->sqs = $sqs;
        $this->prefix = $prefix;
        $this->default = $default;
        $this->suffix = $suffix;
    }

    /**
     * Get the size of the queue.
     *
     * @param string|null $queue
     *
     * @return int
     */
    public function size($queue = null)
    {
        $response = $this->sqs->getQueueAttributes([
            'QueueUrl' => $this->getQueue($queue),
            'AttributeNames' => [QueueAttributeName::APPROXIMATE_NUMBER_OF_MESSAGES],
        ]);

        $attributes = $response->getAttributes();

        return (int) $attributes[QueueAttributeName::APPROXIMATE_NUMBER_OF_MESSAGES];
    }

    /**
     * Push a new job onto the queue.
     *
     * @param \Closure|string|object $job
     * @param mixed                  $data
     * @param string|null            $queue
     *
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        return $this->pushRaw($this->createPayload($job, $queue ?: $this->default, $data), $queue);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param string      $payload
     * @param string|null $queue
     *
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        return $this->sqs->sendMessage([
            'QueueUrl' => $this->getQueue($queue), 'MessageBody' => $payload,
        ])->getMessageId();
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param \DateTimeInterface|\DateInterval|int $delay
     * @param \Closure|string|object               $job
     * @param mixed                                $data
     * @param string|null                          $queue
     *
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->sqs->sendMessage([
            'QueueUrl' => $this->getQueue($queue),
            'MessageBody' => $this->createPayload($job, $queue ?: $this->default, $data),
            'DelaySeconds' => $this->secondsUntil($delay),
        ])->getMessageId();
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param string|null $queue
     *
     * @return Job|null
     */
    public function pop($queue = null)
    {
        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue = $this->getQueue($queue),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        foreach ($response->getMessages() as $message) {
            return new AsyncAwsSqsJob(
                $this->container,
                $this->sqs,
                $message,
                $this->connectionName,
                $queue
            );
        }

        return null;
    }

    /**
     * Get the queue or return the default.
     *
     * @param string|null $queue
     *
     * @return string
     */
    public function getQueue($queue)
    {
        $queue = $queue ?: $this->default;

        return false === filter_var($queue, \FILTER_VALIDATE_URL)
            ? rtrim($this->prefix, '/') . '/' . Str::finish($queue, $this->suffix)
            : $queue;
    }

    /**
     * Get the underlying SQS instance.
     *
     * @return SqsClient
     */
    public function getSqs()
    {
        return $this->sqs;
    }
}
