<?php

namespace AsyncAws\Illuminate\Cache;

use Illuminate\Cache\Lock;

/**
 * This class is a port from Illuminate\Cache\DynamoDbLock.
 */
class AsyncAwsDynamoDbLock extends Lock
{
    /**
     * The DynamoDB client instance.
     *
     * @var AsyncAwsDynamoDbStore
     */
    private $dynamoDb;

    /**
     * Create a new lock instance.
     *
     * @param string      $name
     * @param int         $seconds
     * @param string|null $owner
     *
     * @return void
     */
    public function __construct(AsyncAwsDynamoDbStore $dynamo, $name, $seconds, $owner = null)
    {
        parent::__construct($name, $seconds, $owner);

        $this->dynamoDb = $dynamo;
    }

    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    public function acquire()
    {
        return $this->dynamoDb->add(
            $this->name, $this->owner, $this->seconds
        );
    }

    /**
     * Release the lock.
     */
    public function release()
    {
        if ($this->isOwnedByCurrentProcess()) {
            $this->dynamoDb->forget($this->name);
        }

        return true;
    }

    /**
     * Release this lock in disregard of ownership.
     *
     * @return void
     */
    public function forceRelease()
    {
        $this->dynamoDb->forget($this->name);
    }

    /**
     * Returns the owner value written into the driver for this lock.
     *
     * @return string
     */
    protected function getCurrentOwner()
    {
        return $this->dynamoDb->get($this->name);
    }
}
