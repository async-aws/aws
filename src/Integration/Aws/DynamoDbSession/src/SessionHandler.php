<?php

namespace AsyncAws\DynamoDbSession;

use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Enum\BillingMode;
use AsyncAws\DynamoDb\Enum\KeyType;
use AsyncAws\DynamoDb\Enum\ScalarAttributeType;
use AsyncAws\DynamoDb\Exception\ConditionalCheckFailedException;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

class SessionHandler implements \SessionHandlerInterface
{
    /**
     * @var DynamoDbClient
     */
    private $client;

    /**
     * @var array{
     *   consistent_read: bool,
     *   data_attribute: string,
     *   hash_key: string,
     *   session_lifetime?: int,
     *   session_lifetime_attribute: string,
     *   table_name: string,
     *   id_separator: string,
     *   locking: bool,
     *   max_lock_wait_time: float,
     *   min_lock_retry_microtime: int<0, max>,
     *   max_lock_retry_microtime: int<0, max>,
     * }
     */
    private $options;

    /**
     * @var string
     */
    private $dataRead = '';

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $sessionName;

    /**
     * @var bool
     */
    private $sessionWritten;

    /**
     * @param array{
     *   consistent_read?: bool,
     *   data_attribute?: string,
     *   hash_key?: string,
     *   session_lifetime?: int,
     *   session_lifetime_attribute?: string,
     *   table_name: string,
     *   id_separator?: string,
     *   locking?: bool,
     *   max_lock_wait_time?: int|float,
     *   min_lock_retry_microtime?: int<0, max>,
     *   max_lock_retry_microtime?: int<0, max>,
     * } $options
     */
    public function __construct(DynamoDbClient $client, array $options)
    {
        $this->client = $client;
        $options['data_attribute'] = $options['data_attribute'] ?? 'data';
        $options['hash_key'] = $options['hash_key'] ?? 'id';
        $options['session_lifetime_attribute'] = $options['session_lifetime_attribute'] ?? 'expires';
        $options['id_separator'] = $options['id_separator'] ?? '_';
        $options['consistent_read'] = $options['consistent_read'] ?? true;
        $options['locking'] = $options['locking'] ?? false;
        $options['max_lock_wait_time'] = $options['max_lock_wait_time'] ?? 10.0;
        $options['min_lock_retry_microtime'] = $options['min_lock_retry_microtime'] ?? 10000;
        $options['max_lock_retry_microtime'] = $options['max_lock_retry_microtime'] ?? 50000;
        $this->options = $options;
    }

    public function setUp(): void
    {
        $this->client->createTable([
            'TableName' => $this->options['table_name'],
            'BillingMode' => BillingMode::PAY_PER_REQUEST,
            'AttributeDefinitions' => [
                [
                    'AttributeName' => $this->options['hash_key'],
                    'AttributeType' => ScalarAttributeType::S,
                ],
            ],
            'KeySchema' => [
                [
                    'AttributeName' => $this->options['hash_key'],
                    'KeyType' => KeyType::HASH,
                ],
            ],
        ]);

        $response = $this->client->tableExists(['TableName' => $this->options['table_name']]);
        $response->wait(100, 3);
        if (!$response->isSuccess()) {
            throw new RuntimeException(sprintf('Could not create table %s', $this->options['table_name']));
        }

        $this->client->updateTimeToLive([
            'TableName' => $this->options['table_name'],
            'TimeToLiveSpecification' => [
                'Enabled' => true,
                'AttributeName' => $this->options['session_lifetime_attribute'],
            ],
        ]);
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function close()
    {
        $id = session_id();

        // Make sure the expiration time is updated, even if the write did not occur
        if (false !== $id && ($this->sessionId !== $id || !$this->sessionWritten)) {
            $this->sessionWritten = $this->doWrite($id, false);
        }

        return $this->sessionWritten;
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function destroy($sessionId)
    {
        $this->sessionId = $sessionId;

        $this->client->deleteItem([
            'TableName' => $this->options['table_name'],
            'Key' => $this->formatKey($this->formatId($sessionId)),
        ]);

        return $this->sessionWritten = true;
    }

    /**
     * @return int|false
     */
    #[\ReturnTypeWillChange]
    public function gc($maxLifetime)
    {
        // DynamoDB takes care of garbage collection
        return 0;
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function open($savePath, $name)
    {
        $this->sessionName = $name;

        return true;
    }

    /**
     * @return string
     */
    #[\ReturnTypeWillChange]
    public function read($sessionId)
    {
        $this->sessionId = $sessionId;

        $this->dataRead = '';

        $key = $this->formatKey($this->formatId($sessionId));
        $attributes = $this->options['locking'] ? $this->readLocked($key) : $this->readNonLocked($key);

        // Return the data if it is not expired. If it is expired, remove it
        if (isset($attributes[$this->options['session_lifetime_attribute']]) && isset($attributes[$this->options['data_attribute']])) {
            $this->dataRead = $attributes[$this->options['data_attribute']]->getS() ?? '';
            if ($attributes[$this->options['session_lifetime_attribute']]->getN() <= time()) {
                $this->dataRead = '';
                $this->destroy($sessionId);
            }
        }

        return $this->dataRead;
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function write($sessionId, $sessionData)
    {
        $this->sessionId = $sessionId;

        $sessionIdChanged = $sessionId !== $this->sessionId;
        $sessionDataChanged = $sessionData !== $this->dataRead;
        $updateData = $sessionIdChanged || $sessionDataChanged;

        return $this->sessionWritten = $this->doWrite($sessionId, $updateData, $sessionData);
    }

    private function doWrite(string $id, bool $updateData, string $data = ''): bool
    {
        $expires = time() + ($this->options['session_lifetime'] ?? (int) \ini_get('session.gc_maxlifetime'));

        $attributes = [
            $this->options['session_lifetime_attribute'] => ['Value' => ['N' => (string) $expires]],
        ];

        if ($this->options['locking']) {
            $attributes['lock'] = ['Action' => 'DELETE'];
        }

        if ($updateData) {
            $attributes[$this->options['data_attribute']] = '' != $data
                ? ['Value' => ['S' => $data]]
                : ['Action' => 'DELETE'];
        }

        $this->client->updateItem([
            'TableName' => $this->options['table_name'],
            'Key' => $this->formatKey($this->formatId($id)),
            'AttributeUpdates' => $attributes,
        ]);

        if ($updateData) {
            $this->dataRead = $data;
        }

        return true;
    }

    private function formatId(string $id): string
    {
        return trim($this->sessionName . $this->options['id_separator'] . $id, $this->options['id_separator']);
    }

    /**
     * @return array<string, array{S: string}>
     */
    private function formatKey(string $key): array
    {
        return [$this->options['hash_key'] => ['S' => $key]];
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function readNonLocked(array $key): array
    {
        return $this->client->getItem([
            'TableName' => $this->options['table_name'],
            'Key' => $key,
            'ConsistentRead' => $this->options['consistent_read'],
        ])->getItem();
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function readLocked(array $key): array
    {
        $timeout = microtime(true) + $this->options['max_lock_wait_time'];

        while (true) {
            try {
                return $this->client->updateItem([
                    'TableName' => $this->options['table_name'],
                    'Key' => $key,
                    'ConsistentRead' => $this->options['consistent_read'],
                    'Expected' => ['lock' => ['Exists' => false]],
                    'AttributeUpdates' => ['lock' => ['Value' => ['N' => '1']]],
                    'ReturnValues' => 'ALL_NEW',
                ])->getAttributes();
            } catch (ConditionalCheckFailedException $e) {
                // If we were to exceed the timeout after sleep, let's give up immediately.
                $sleep = rand($this->options['min_lock_retry_microtime'], $this->options['max_lock_retry_microtime']);
                if (microtime(true) + $sleep * 1e-6 > $timeout) {
                    throw $e;
                }

                usleep($sleep);
            }
        }
    }
}
