<?php

namespace AsyncAws\Illuminate\Cache;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Enum\KeyType;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Str;

/**
 * This class is a port from Illuminate\Cache\DynamoDbStore.
 */
class AsyncAwsDynamoDbStore implements LockProvider, Store
{
    use InteractsWithTime;

    /**
     * The DynamoDB client instance.
     *
     * @var DynamoDbClient
     */
    private $dynamoDb;

    /**
     * The table name.
     *
     * @var string
     */
    private $table;

    /**
     * The name of the attribute that should hold the key.
     *
     * @var string
     */
    private $keyAttribute;

    /**
     * The name of the attribute that should hold the value.
     *
     * @var string
     */
    private $valueAttribute;

    /**
     * The name of the attribute that should hold the expiration timestamp.
     *
     * @var string
     */
    private $expirationAttribute;

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    private $prefix;

    /**
     * Create a new store instance.
     *
     * @param string $table
     * @param string $keyAttribute
     * @param string $valueAttribute
     * @param string $expirationAttribute
     * @param string $prefix
     *
     * @return void
     */
    public function __construct(
        DynamoDbClient $dynamo,
        $table,
        $keyAttribute = 'key',
        $valueAttribute = 'value',
        $expirationAttribute = 'expires_at',
        $prefix = ''
    ) {
        $this->table = $table;
        $this->dynamoDb = $dynamo;
        $this->keyAttribute = $keyAttribute;
        $this->valueAttribute = $valueAttribute;
        $this->expirationAttribute = $expirationAttribute;

        $this->setPrefix($prefix);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string|array $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (\is_array($key)) {
            return $this->many($key);
        }

        $response = $this->dynamoDb->getItem([
            'TableName' => $this->table,
            'ConsistentRead' => false,
            'Key' => [
                $this->keyAttribute => [
                    'S' => $this->prefix . $key,
                ],
            ],
        ]);

        $item = $response->getItem();
        if (empty($item)) {
            return;
        }

        if ($this->isExpired($item)) {
            return;
        }

        if (isset($item[$this->valueAttribute])) {
            return $this->unserialize(
                $item[$this->valueAttribute]->getS() ??
                $item[$this->valueAttribute]->getN() ??
                null
            );
        }
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @return array
     */
    public function many(array $keys)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }

        // TODO Use BatchGetItem. Blocked by https://github.com/async-aws/aws/issues/566
        return $result;
    }

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $seconds
     *
     * @return bool
     */
    public function put($key, $value, $seconds)
    {
        $this->dynamoDb->putItem([
            'TableName' => $this->table,
            'Item' => [
                $this->keyAttribute => [
                    'S' => $this->prefix . $key,
                ],
                $this->valueAttribute => [
                    $this->type($value) => $this->serialize($value),
                ],
                $this->expirationAttribute => [
                    'N' => (string) $this->toTimestamp($seconds),
                ],
            ],
        ]);

        return true;
    }

    /**
     * Store multiple items in the cache for a given number of $seconds.
     *
     * @param int $seconds
     *
     * @return bool
     */
    public function putMany(array $values, $seconds)
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value, $seconds);
        }

        // TODO Use BatchWriteItem. Blocked by https://github.com/async-aws/aws/issues/566
        return true;
    }

    /**
     * Store an item in the cache if the key doesn't exist.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $seconds
     *
     * @return bool
     */
    public function add($key, $value, $seconds)
    {
        try {
            $this->dynamoDb->putItem([
                'TableName' => $this->table,
                'Item' => [
                    $this->keyAttribute => [
                        'S' => $this->prefix . $key,
                    ],
                    $this->valueAttribute => [
                        $this->type($value) => $this->serialize($value),
                    ],
                    $this->expirationAttribute => [
                        'N' => (string) $this->toTimestamp($seconds),
                    ],
                ],
                'ConditionExpression' => 'attribute_not_exists(#key) OR #expires_at < :now',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#expires_at' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':now' => [
                        'N' => (string) Carbon::now()->getTimestamp(),
                    ],
                ],
            ]);

            return true;
        } catch (HttpException $e) {
            $type = $e->getAwsType();
            if (null !== $type && Str::contains($type, 'ConditionalCheckFailedException')) {
                return false;
            }

            throw $e;
        }
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        try {
            $response = $this->dynamoDb->updateItem([
                'TableName' => $this->table,
                'Key' => [
                    $this->keyAttribute => [
                        'S' => $this->prefix . $key,
                    ],
                ],
                'ConditionExpression' => 'attribute_exists(#key) AND #expires_at > :now',
                'UpdateExpression' => 'SET #value = #value + :amount',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#value' => $this->valueAttribute,
                    '#expires_at' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':now' => [
                        'N' => (string) Carbon::now()->getTimestamp(),
                    ],
                    ':amount' => [
                        'N' => (string) $value,
                    ],
                ],
                'ReturnValues' => 'UPDATED_NEW',
            ]);

            return (int) $response->getAttributes()[$this->valueAttribute]->getN();
        } catch (HttpException $e) {
            $type = $e->getAwsType();
            if (null !== $type && Str::contains($type, 'ConditionalCheckFailedException')) {
                return false;
            }

            throw $e;
        }
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return int|bool
     */
    public function decrement($key, $value = 1)
    {
        try {
            $response = $this->dynamoDb->updateItem([
                'TableName' => $this->table,
                'Key' => [
                    $this->keyAttribute => [
                        'S' => $this->prefix . $key,
                    ],
                ],
                'ConditionExpression' => 'attribute_exists(#key) AND #expires_at > :now',
                'UpdateExpression' => 'SET #value = #value - :amount',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#value' => $this->valueAttribute,
                    '#expires_at' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':now' => [
                        'N' => (string) Carbon::now()->getTimestamp(),
                    ],
                    ':amount' => [
                        'N' => (string) $value,
                    ],
                ],
                'ReturnValues' => 'UPDATED_NEW',
            ]);

            return (int) $response->getAttributes()[$this->valueAttribute]->getN();
        } catch (HttpException $e) {
            $type = $e->getAwsType();
            if (null !== $type && Str::contains($type, 'ConditionalCheckFailedException')) {
                return false;
            }

            throw $e;
        }
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function forever($key, $value)
    {
        return $this->put($key, $value, Carbon::now()->addYears(5)->getTimestamp());
    }

    /**
     * Get a lock instance.
     *
     * @param string      $name
     * @param int         $seconds
     * @param string|null $owner
     *
     * @return AsyncAwsDynamoDbLock
     */
    public function lock($name, $seconds = 0, $owner = null)
    {
        return new AsyncAwsDynamoDbLock($this, $this->prefix . $name, $seconds, $owner);
    }

    /**
     * Restore a lock instance using the owner identifier.
     *
     * @param string $name
     * @param string $owner
     *
     * @return AsyncAwsDynamoDbLock
     */
    public function restoreLock($name, $owner)
    {
        return $this->lock($name, 0, $owner);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget($key)
    {
        $this->dynamoDb->deleteItem([
            'TableName' => $this->table,
            'Key' => [
                $this->keyAttribute => [
                    'S' => $this->prefix . $key,
                ],
            ],
        ]);

        return true;
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function flush()
    {
        try {
            // Delete the table
            $this->dynamoDb->deleteTable(['TableName' => $this->table]);
        } catch (HttpException $e) {
            $code = (int) $e->getCode();
            if (404 !== $code) {
                // Any error but "table not found"
                throw new RuntimeException('Could not flush DynamoDb cache. Table could not be deleted.', $code, $e);
            }
        }

        // Wait until table is removed
        $response = $this->dynamoDb->tableNotExists(['TableName' => $this->table]);
        $response->wait(100, 3);
        if (!$response->isSuccess()) {
            throw new RuntimeException('Could not flush DynamoDb cache. Table could not be deleted.');
        }

        // Create a new table
        $this->dynamoDb->createTable([
            'TableName' => $this->table,
            'KeySchema' => [
                new KeySchemaElement(['AttributeName' => 'key', 'KeyType' => KeyType::HASH]),
            ],
        ]);

        // Wait until table is created
        $response = $this->dynamoDb->tableExists(['TableName' => $this->table]);
        $response->wait(100, 3);
        if (!$response->isSuccess()) {
            throw new RuntimeException('Could not flush DynamoDb cache. Table could not be created.');
        }

        return true;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the cache key prefix.
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = !empty($prefix) ? $prefix . ':' : '';
    }

    /**
     * Determine if the given item is expired.
     *
     * @param \DateTimeInterface|null $expiration
     *
     * @return bool
     */
    private function isExpired(array $item, $expiration = null)
    {
        $expiration = $expiration ?: Carbon::now();

        return isset($item[$this->expirationAttribute]) &&
               $expiration->getTimestamp() >= $item[$this->expirationAttribute]->getN();
    }

    /**
     * Get the UNIX timestamp for the given number of seconds.
     *
     * @param int $seconds
     *
     * @return int
     */
    private function toTimestamp($seconds)
    {
        return $seconds > 0
                    ? $this->availableAt($seconds)
                    : Carbon::now()->getTimestamp();
    }

    /**
     * Serialize the value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function serialize($value)
    {
        return is_numeric($value) ? (string) $value : serialize($value);
    }

    /**
     * Unserialize the value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function unserialize($value)
    {
        if (false !== filter_var($value, \FILTER_VALIDATE_INT)) {
            return (int) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return unserialize($value);
    }

    /**
     * Get the DynamoDB type for the given value.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function type($value)
    {
        return is_numeric($value) ? 'N' : 'S';
    }
}
