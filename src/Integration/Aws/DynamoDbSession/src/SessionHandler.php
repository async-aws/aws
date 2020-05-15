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
     *   table_name: string
     * } $config
     */
    public function __construct(DynamoDbClient $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
        $this->config['data_attribute'] = $this->config['data_attribute'] ?? 'data';
        $this->config['session_lifetime_attribute'] = $this->config['session_lifetime_attribute'] ?? 'expires';
    }

    public function close()
    {
        $id = \session_id();

        // Make sure the session is unlocked and the expiration time is updated, even if the write did not occur
        if ($this->sessionId !== $id || !$this->sessionWritten) {
            $this->sessionWritten = $this->doWrite($id, '', false);
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

        // Return the data if it is not expired. If it is expired, remove it
        if (isset($attributes[$this->config['session_lifetime_attribute']]) && isset($attributes[$this->config['data_attribute']])) {
            $this->dataRead = $attributes[$this->config['data_attribute']]->getS() ?? '';
            if ($attributes[$this->config['session_lifetime_attribute']]->getN() <= time()) {
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

        return $this->sessionWritten = $this->doWrite($session_id, $session_data, $changed);
    }

    private function doWrite(string $id, string $data, bool $isChanged): bool
    {
        $expires = time() + ($this->config['session_lifetime'] ?? (int) ini_get('session.gc_maxlifetime'));

        $attributes = [
            $this->config['session_lifetime_attribute'] => ['Value' => ['N' => (string) $expires]],
        ];

        if ($isChanged) {
            $attributes[$this->config['data_attribute']] = '' != $data
                ? ['Value' => ['S' => $data]]
                : ['Action' => 'DELETE'];
        }

        $this->client->updateItem([
            'TableName' => $this->config['table_name'],
            'Key' => $this->formatKey($this->formatId($id)),
            'AttributeUpdates' => $attributes,
        ]);

        return true;
    }

    private function formatId(string $id): string
    {
        return trim($this->sessionName . '_' . $id, '_');
    }

    private function formatKey(string $key): array
    {
        return [$this->config['hash_key'] ?? 'id' => ['S' => $key]];
    }
}
