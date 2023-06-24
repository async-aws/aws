<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings to insert a DVB Network Information Table (NIT) in the transport stream of this output. When you
 * work directly in your JSON job specification, include this object only when your job has a transport stream output
 * and the container settings contain the object M2tsSettings.
 */
final class DvbNitSettings
{
    /**
     * The numeric value placed in the Network Information Table (NIT).
     */
    private $networkId;

    /**
     * The network name text placed in the network_name_descriptor inside the Network Information Table. Maximum length is
     * 256 characters.
     */
    private $networkName;

    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     */
    private $nitInterval;

    /**
     * @param array{
     *   NetworkId?: null|int,
     *   NetworkName?: null|string,
     *   NitInterval?: null|int,
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
     *   NetworkId?: null|int,
     *   NetworkName?: null|string,
     *   NitInterval?: null|int,
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
