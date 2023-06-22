<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings to insert a DVB Time and Date Table (TDT) in the transport stream of this output. When you work
 * directly in your JSON job specification, include this object only when your job has a transport stream output and the
 * container settings contain the object M2tsSettings.
 */
final class DvbTdtSettings
{
    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     */
    private $tdtInterval;

    /**
     * @param array{
     *   TdtInterval?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tdtInterval = $input['TdtInterval'] ?? null;
    }

    /**
     * @param array{
     *   TdtInterval?: null|int,
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
