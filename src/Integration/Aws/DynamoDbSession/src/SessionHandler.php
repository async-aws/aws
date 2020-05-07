<?php

namespace AsyncAws\DynamoDbSession;

use AsyncAws\DynamoDb\DynamoDbClient;

/**
 * This is a port from Aws\DynamoDb\SessionHandler.
 */
class SessionHandler implements \SessionHandlerInterface
{
    /**
     * @var DynamoDbClient
     */
    private $dynamoDb;

    /**
     * @var string Session save path.
     */
    private $savePath;

    /**
     * @var string Session name.
     */
    private $sessionName;

    /**
     * @var string The last known session ID
     */
    private $openSessionId = '';

    /**
     * @var string Stores serialized data for tracking changes.
     */
    private $dataRead = '';

    /**
     * @var bool Keeps track of whether the session has been written.
     */
    private $sessionWritten = false;

    public function __construct(DynamoDbClient $dynamoDb)
    {
        $this->dynamoDb = $dynamoDb;
    }

    /**
     * Register the DynamoDB session handler.
     *
     * @return bool whether or not the handler was registered
     */
    public function register(): bool
    {
        return session_set_save_handler($this, true);
    }

    /**
     * Open a session for writing. Triggered by session_start().
     *
     * @param string $savePath    session save path
     * @param string $sessionName session name
     *
     * @return bool whether or not the operation succeeded
     */
    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        $this->sessionName = $sessionName;

        return true;
    }

    /**
     * Close a session from writing.
     *
     * @return bool Success
     */
    public function close()
    {
        $id = session_id();
        // Make sure the session is unlocked and the expiration time is updated,
        // even if the write did not occur
        if ($this->openSessionId !== $id || !$this->sessionWritten) {
            $result = $this->connection->write($this->formatId($id), '', false);
            $this->sessionWritten = (bool) $result;
        }

        return $this->sessionWritten;
    }

    /**
     * Read a session stored in DynamoDB.
     *
     * @param string $id session ID
     *
     * @return string session data
     */
    public function read($id)
    {
        $this->openSessionId = $id;
        // PHP expects an empty string to be returned from this method if no
        // data is retrieved
        $this->dataRead = '';

        // Get session data using the selected locking strategy
        $item = $this->connection->read($this->formatId($id));

        $dataAttribute = $this->connection->getDataAttribute();
        $sessionLifetimeAttribute = $this->connection->getSessionLifetimeAttribute();

        // Return the data if it is not expired. If it is expired, remove it
        if (isset($item[$sessionLifetimeAttribute]) && isset($item[$dataAttribute])) {
            $this->dataRead = $item[$dataAttribute];
            if ($item[$sessionLifetimeAttribute] <= time()) {
                $this->dataRead = '';
                $this->destroy($id);
            }
        }

        return $this->dataRead;
    }

    /**
     * Write a session to DynamoDB.
     *
     * @param string $id   session ID
     * @param string $data serialized session data to write
     *
     * @return bool whether or not the operation succeeded
     */
    public function write($id, $data)
    {
        $changed = $id !== $this->openSessionId
            || $data !== $this->dataRead;
        $this->openSessionId = $id;

        // Write the session data using the selected locking strategy
        $this->sessionWritten = $this->connection
            ->write($this->formatId($id), $data, $changed);

        return $this->sessionWritten;
    }

    /**
     * Delete a session stored in DynamoDB.
     *
     * @param string $id session ID
     *
     * @return bool whether or not the operation succeeded
     */
    public function destroy($id)
    {
        $this->openSessionId = $id;
        // Delete the session data using the selected locking strategy
        $this->sessionWritten
            = $this->connection->delete($this->formatId($id));

        return $this->sessionWritten;
    }

    /**
     * Satisfies the session handler interface, but does nothing. To do garbage
     * collection, you must manually call the garbageCollect() method.
     *
     * @param int $maxLifetime ignored
     *
     * @return bool whether or not the operation succeeded
     */
    public function gc($maxLifetime)
    {
        // Garbage collection for a DynamoDB table must be triggered manually.
        return true;
    }

    /**
     * Triggers garbage collection on expired sessions.
     *
     */
    public function garbageCollect()
    {
        $this->connection->deleteExpired();
    }

    /**
     * Prepend the session ID with the session name.
     *
     * @param string $id the session ID
     *
     * @return string prepared session ID
     */
    private function formatId($id)
    {
        return trim($this->sessionName . '_' . $id, '_');
    }
}
