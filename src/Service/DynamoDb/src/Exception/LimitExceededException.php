<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * There is no limit to the number of daily on-demand backups that can be taken.
 * Up to 50 simultaneous table operations are allowed per account. These operations include `CreateTable`,
 * `UpdateTable`, `DeleteTable`,`UpdateTimeToLive`, `RestoreTableFromBackup`, and `RestoreTableToPointInTime`.
 * The only exception is when you are creating a table with one or more secondary indexes. You can have up to 25 such
 * requests running at a time; however, if the table or index specifications are complex, DynamoDB might temporarily
 * reduce the number of concurrent operations.
 * There is a soft account quota of 256 tables.
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
