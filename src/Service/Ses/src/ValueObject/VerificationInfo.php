<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Ses\Enum\VerificationError;

/**
 * An object that contains additional information about the verification status for the identity.
 */
final class VerificationInfo
{
    /**
     * The last time a verification attempt was made for this identity.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastCheckedTimestamp;

    /**
     * The last time a successful verification was made for this identity.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastSuccessTimestamp;

    /**
     * Provides the reason for the failure describing why Amazon SES was not able to successfully verify the identity. Below
     * are the possible values:
     *
     * - `INVALID_VALUE` – Amazon SES was able to find the record, but the value contained within the record was invalid.
     *   Ensure you have published the correct values for the record.
     * - `TYPE_NOT_FOUND` – The queried hostname exists but does not have the requested type of DNS record. Ensure that
     *   you have published the correct type of DNS record.
     * - `HOST_NOT_FOUND` – The queried hostname does not exist or was not reachable at the time of the request. Ensure
     *   that you have published the required DNS record(s).
     * - `SERVICE_ERROR` – A temporary issue is preventing Amazon SES from determining the verification status of the
     *   domain.
     * - `DNS_SERVER_ERROR` – The DNS server encountered an issue and was unable to complete the request.
     * - `REPLICATION_ACCESS_DENIED` – The verification failed because the user does not have the required permissions to
     *   replicate the DKIM key from the primary region. Ensure you have the necessary permissions in both primary and
     *   replica regions.
     * - `REPLICATION_PRIMARY_NOT_FOUND` – The verification failed because no corresponding identity was found in the
     *   specified primary region. Ensure the identity exists in the primary region before attempting replication.
     * - `REPLICATION_PRIMARY_BYO_DKIM_NOT_SUPPORTED` – The verification failed because the identity in the primary region
     *   is configured with Bring Your Own DKIM (BYODKIM). DKIM key replication is only supported for identities using Easy
     *   DKIM.
     * - `REPLICATION_REPLICA_AS_PRIMARY_NOT_SUPPORTED` – The verification failed because the specified primary identity
     *   is a replica of another identity, and multi-level replication is not supported; the primary identity must be a
     *   non-replica identity.
     * - `REPLICATION_PRIMARY_INVALID_REGION` – The verification failed due to an invalid primary region specified. Ensure
     *   you provide a valid Amazon Web Services region where Amazon SES is available and different from the replica region.
     *
     * @var VerificationError::*|null
     */
    private $errorType;

    /**
     * An object that contains information about the start of authority (SOA) record associated with the identity.
     *
     * @var SOARecord|null
     */
    private $soaRecord;

    /**
     * @param array{
     *   LastCheckedTimestamp?: \DateTimeImmutable|null,
     *   LastSuccessTimestamp?: \DateTimeImmutable|null,
     *   ErrorType?: VerificationError::*|null,
     *   SOARecord?: SOARecord|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lastCheckedTimestamp = $input['LastCheckedTimestamp'] ?? null;
        $this->lastSuccessTimestamp = $input['LastSuccessTimestamp'] ?? null;
        $this->errorType = $input['ErrorType'] ?? null;
        $this->soaRecord = isset($input['SOARecord']) ? SOARecord::create($input['SOARecord']) : null;
    }

    /**
     * @param array{
     *   LastCheckedTimestamp?: \DateTimeImmutable|null,
     *   LastSuccessTimestamp?: \DateTimeImmutable|null,
     *   ErrorType?: VerificationError::*|null,
     *   SOARecord?: SOARecord|array|null,
     * }|VerificationInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return VerificationError::*|null
     */
    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function getLastCheckedTimestamp(): ?\DateTimeImmutable
    {
        return $this->lastCheckedTimestamp;
    }

    public function getLastSuccessTimestamp(): ?\DateTimeImmutable
    {
        return $this->lastSuccessTimestamp;
    }

    public function getSoaRecord(): ?SOARecord
    {
        return $this->soaRecord;
    }
}
