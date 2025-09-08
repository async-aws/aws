<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings to insert a DVB Time and Date Table (TDT) in the transport stream of this output.
 */
final class DvbTdtSettings
{
    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     *
     * @var int|null
     */
    private $tdtInterval;

    /**
     * @param array{
     *   TdtInterval?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tdtInterval = $input['TdtInterval'] ?? null;
    }

    /**
     * @param array{
     *   TdtInterval?: int|null,
     * }|DvbTdtSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTdtInterval(): ?int
    {
        return $this->tdtInterval;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->tdtInterval) {
            $payload['tdtInterval'] = $v;
        }

        return $payload;
    }
}
