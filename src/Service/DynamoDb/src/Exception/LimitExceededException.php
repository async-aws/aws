<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * There is no limit to the number of daily on-demand backups that can be taken.
 *
 * For most purposes, up to 500 simultaneous table operations are allowed per account. These operations include
 * `CreateTable`, `UpdateTable`, `DeleteTable`,`UpdateTimeToLive`, `RestoreTableFromBackup`, and
 * `RestoreTableToPointInTime`.
 *
 * When you are creating a table with one or more secondary indexes, you can have up to 250 such requests running at a
 * time. However, if the table or index specifications are complex, then DynamoDB might temporarily reduce the number of
 * concurrent operations.
 *
 * When importing into DynamoDB, up to 50 simultaneous import table operations are allowed per account.
 *
 * There is a soft account quota of 2,500 tables.
 *
 * GetRecords was called with a value of more than 1000 for the limit request parameter.
 *
 * More than 2 processes are reading from the same streams shard at the same time. Exceeding this limit may result in
 * request throttling.
 */
final class LimitExceededException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
