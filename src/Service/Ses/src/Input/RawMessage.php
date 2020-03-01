<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class RawMessage
{
    /**
     * The raw email message. The message has to meet the following criteria:.
     *
     * @required
     *
     * @var string|null
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

    public function getData(): ?string
    {
        return $this->Data;
    }

    public function setData(?string $value): self
    {
        $this->Data = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->Data) {
            throw new InvalidArgument(sprintf('Missing parameter "Data" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
