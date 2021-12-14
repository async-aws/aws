<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteSecretResponse extends Result
{
    /**
     * The ARN of the secret.
     */
    private $arn;

    /**
     * The name of the secret.
     */
    private $name;

    /**
     * The date and time after which this secret Secrets Manager can permanently delete this secret, and it can no longer be
     * restored. This value is the date and time of the delete request plus the number of days in `RecoveryWindowInDays`.
     */
    private $deletionDate;

    public function getArn(): ?string
    {
        $this->initialize();

        return $this->arn;
    }

    public function getDeletionDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->deletionDate;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->arn = isset($data['ARN']) ? (string) $data['ARN'] : null;
        $this->name = isset($data['Name']) ? (string) $data['Name'] : null;
        $this->deletionDate = (isset($data['DeletionDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['DeletionDate'])))) ? $d : null;
    }
}
