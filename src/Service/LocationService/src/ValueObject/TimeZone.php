<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about a time zone. Includes the name of the time zone and the offset from UTC in seconds.
 */
final class TimeZone
{
    /**
     * The name of the time zone, following the IANA time zone standard [^1]. For example, `America/Los_Angeles`.
     *
     * [^1]: https://www.iana.org/time-zones
     *
     * @var string
     */
    private $name;

    /**
     * The time zone's offset, in seconds, from UTC.
     *
     * @var int|null
     */
    private $offset;

    /**
     * @param array{
     *   Name: string,
     *   Offset?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->offset = $input['Offset'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Offset?: int|null,
     * }|TimeZone $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
