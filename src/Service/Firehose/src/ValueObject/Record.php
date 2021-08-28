<?php

namespace AsyncAws\Firehose\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The record.
 */
final class Record
{
    /**
     * The data blob, which is base64-encoded when the blob is serialized. The maximum size of the data blob, before
     * base64-encoding, is 1,000 KiB.
     */
    private $data;

    /**
     * @param array{
     *   Data: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = $input['Data'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->data) {
            throw new InvalidArgument(sprintf('Missing parameter "Data" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Data'] = base64_encode($v);

        return $payload;
    }
}
