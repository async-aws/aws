<?php

namespace AsyncAws\Firehose\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The unit of data in a delivery stream.
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
        $this->data = $input['Data'] ?? $this->throwException(new InvalidArgument('Missing required field "Data".'));
    }

    /**
     * @param array{
     *   Data: string,
     * }|Record $input
     */
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
        $v = $this->data;
        $payload['Data'] = base64_encode($v);

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
