<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteSecretResponse extends Result
{
    /**
     * The ARN of the secret that is now scheduled for deletion.
     */
    private $arn;

    /**
     * The friendly name of the secret currently scheduled for deletion.
     */
    private $name;

    /**
     * The date and time after which this secret can be deleted by Secrets Manager and can no longer be restored. This value
     * is the date and time of the delete request plus the number of days specified in `RecoveryWindowInDays`.
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
