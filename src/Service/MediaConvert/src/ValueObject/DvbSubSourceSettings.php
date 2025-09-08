<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * DVB Sub Source Settings.
 */
final class DvbSubSourceSettings
{
    /**
     * When using DVB-Sub with Burn-in, use this PID for the source content. Unused for DVB-Sub passthrough. All DVB-Sub
     * content is passed through, regardless of selectors.
     *
     * @var int|null
     */
    private $pid;

    /**
     * @param array{
     *   Pid?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->pid = $input['Pid'] ?? null;
    }

    /**
     * @param array{
     *   Pid?: int|null,
     * }|DvbSubSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPid(): ?int
    {
        return $this->pid;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->pid) {
            $payload['pid'] = $v;
        }

        return $payload;
    }
}
