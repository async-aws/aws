<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The execution of the SQL statement timed out.
 */
final class StatementTimeoutException extends ClientException
{
    /**
     * The database connection ID that executed the SQL statement.
     */
    private $dbConnectionId;

    public function getDbConnectionId(): ?string
    {
        return $this->dbConnectionId;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->dbConnectionId = isset($data['dbConnectionId']) ? (string) $data['dbConnectionId'] : null;
        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
