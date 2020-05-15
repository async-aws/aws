<?php

namespace AsyncAws\DynamoDbSession;

use AsyncAws\DynamoDb\DynamoDbClient;

class SessionHandler implements \SessionHandlerInterface
{
    /**
     * @var DynamoDbClient
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $dataRead;

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
     * @param DynamoDbClient $client
     * @param array{
     *   consistent_read?: bool,
     *   data_attribute?: string,
     *   hash_key?: string,
     *   session_lifetime?: int,
     *   session_lifetime_attribute?: string,
     *   table_name: string
     * } $config
     */
    public function __construct(DynamoDbClient $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function close()
    {
        $id = \session_id();

        // Make sure the session is unlocked and the expiration time is updated, even if the write did not occur
        if ($this->sessionId !== $id || !$this->sessionWritten) {
            $this->sessionWritten = $this->doWrite($this->formatId($id), '', false);
        }

        return $this->sessionWritten;
    }

    public function destroy($session_id)
    {
        $this->sessionId = $session_id;

        $this->client->deleteItem([
            'TableName' => $this->config['table_name'],
            'Key' => $this->formatKey($this->formatId($session_id)),
        ]);

        return $this->sessionWritten = true;
    }

    public function gc($maxlifetime)
    {
        // DynamoDB takes care of garbage collection
        return true;
    }

    public function open($save_path, $name)
    {
        $this->sessionName = $name;

        return true;
    }

    public function read($session_id)
    {
        $this->sessionId = $session_id;

        $this->dataRead = '';

        $attributes = $this->client->getItem([
            'TableName' => $this->config['table_name'],
            'Key' => $this->formatKey($this->formatId($session_id)),
            'ConsistentRead' => $this->config['consistent_read'] ?? true,
        ])->getItem();

        $dataAttribute = $this->getDataAttribute();
        $lifetimeAttribute = $this->getSessionLifetimeAttribute();

        // Return the data if it is not expired. If it is expired, remove it
        if (isset($attributes[$lifetimeAttribute]) && isset($attributes[$dataAttribute])) {
            $this->dataRead = $attributes[$dataAttribute]->getS();
            if ($attributes[$lifetimeAttribute]->getN() <= time()) {
                $this->dataRead = '';
                $this->destroy($session_id);
            }
        }

        return $this->dataRead;
    }

    public function write($session_id, $session_data)
    {
        $changed = $session_id !== $this->sessionId
            || $session_data !== $this->dataRead;
        $this->sessionId = $session_id;

        return $this->sessionWritten = $this->doWrite($this->formatId($session_id), $session_data, $changed);
    }

    private function doWrite($id, $data, $isChanged): bool
    {
        $expires = time() + ($this->config['session_lifetime'] ?? (int)ini_get('session.gc_maxlifetime'));

        $attributes = [
            $this->getSessionLifetimeAttribute() => ['Value' => ['N' => (string)$expires]],
        ];

        if ($isChanged) {
            $attributes[$this->getDataAttribute()] = $data != ''
                ? ['Value' => ['S' => $data]]
                : ['Action' => 'DELETE'];
        }

        $this->client->updateItem([
            'TableName' => $this->config['table_name'],
            'Key' => $this->formatKey($id),
            'AttributeUpdates' => $attributes,
        ]);

        return true;
    }

    private function formatId($id): string
    {
        return trim($this->sessionName . '_' . $id, '_');
    }

    private function formatKey($key): array
    {
        return [$this->config['hash_key'] ?? 'id' => ['S' => $key]];
    }

    private function getDataAttribute(): string
    {
        return $this->config['data_attribute'] ?? 'data';
    }

    private function getSessionLifetimeAttribute(): string
    {
        return $this->config['session_lifetime_attribute'] ?? 'expires';
    }
}
