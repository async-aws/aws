<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings to insert a DVB Network Information Table (NIT) in the transport stream of this output.
 */
final class DvbNitSettings
{
    /**
     * The numeric value placed in the Network Information Table (NIT).
     *
     * @var int|null
     */
    private $networkId;

    /**
     * The network name text placed in the network_name_descriptor inside the Network Information Table. Maximum length is
     * 256 characters.
     *
     * @var string|null
     */
    private $networkName;

    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     *
     * @var int|null
     */
    private $nitInterval;

    /**
     * @param array{
     *   NetworkId?: int|null,
     *   NetworkName?: string|null,
     *   NitInterval?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->networkId = $input['NetworkId'] ?? null;
        $this->networkName = $input['NetworkName'] ?? null;
        $this->nitInterval = $input['NitInterval'] ?? null;
    }

    /**
     * @param array{
     *   NetworkId?: int|null,
     *   NetworkName?: string|null,
     *   NitInterval?: int|null,
     * }|DvbNitSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNetworkId(): ?int
    {
        return $this->networkId;
    }

    public function getNetworkName(): ?string
    {
        return $this->networkName;
    }

    public function getNitInterval(): ?int
    {
        return $this->nitInterval;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->networkId) {
            $payload['networkId'] = $v;
        }
        if (null !== $v = $this->networkName) {
            $payload['networkName'] = $v;
        }
        if (null !== $v = $this->nitInterval) {
            $payload['nitInterval'] = $v;
        }

        return $payload;
    }
}
