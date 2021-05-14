<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * A complex type that includes the `Comment` and `PrivateZone` elements. If you omitted the `HostedZoneConfig` and
 * `Comment` elements from the request, the `Config` and `Comment` elements don't appear in the response.
 */
final class HostedZoneConfig
{
    /**
     * Any comments that you want to include about the hosted zone.
     */
    private $comment;

    /**
     * A value that indicates whether this is a private hosted zone.
     */
    private $privateZone;

    /**
     * @param array{
     *   Comment?: null|string,
     *   PrivateZone?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->comment = $input['Comment'] ?? null;
        $this->privateZone = $input['PrivateZone'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getPrivateZone(): ?bool
    {
        return $this->privateZone;
    }
}
