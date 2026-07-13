<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that contains information about the start of authority (SOA) record associated with the identity.
 */
final class SOARecord
{
    /**
     * Primary name server specified in the SOA record.
     *
     * @var string|null
     */
    private $primaryNameServer;

    /**
     * Administrative contact email from the SOA record.
     *
     * @var string|null
     */
    private $adminEmail;

    /**
     * Serial number from the SOA record.
     *
     * @var int|null
     */
    private $serialNumber;

    /**
     * @param array{
     *   PrimaryNameServer?: string|null,
     *   AdminEmail?: string|null,
     *   SerialNumber?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->primaryNameServer = $input['PrimaryNameServer'] ?? null;
        $this->adminEmail = $input['AdminEmail'] ?? null;
        $this->serialNumber = $input['SerialNumber'] ?? null;
    }

    /**
     * @param array{
     *   PrimaryNameServer?: string|null,
     *   AdminEmail?: string|null,
     *   SerialNumber?: int|null,
     * }|SOARecord $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdminEmail(): ?string
    {
        return $this->adminEmail;
    }

    public function getPrimaryNameServer(): ?string
    {
        return $this->primaryNameServer;
    }

    public function getSerialNumber(): ?int
    {
        return $this->serialNumber;
    }
}
