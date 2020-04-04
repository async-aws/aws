<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class RawMessage
{
    /**
     * The raw email message. The message has to meet the following criteria:.
     */
    private $Data;

    /**
     * @param array{
     *   Data: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Data = $input['Data'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): string
    {
        return $this->Data;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Data) {
            throw new InvalidArgument(sprintf('Missing parameter "Data" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Data'] = base64_encode($v);

        return $payload;
    }
}
