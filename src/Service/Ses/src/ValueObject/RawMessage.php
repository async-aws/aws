<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class RawMessage
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

    public function validate(): void
    {
        if (null === $this->Data) {
            throw new InvalidArgument(sprintf('Missing parameter "Data" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
