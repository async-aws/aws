<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class Delete
{
    /**
     * The objects to delete.
     *
     * @required
     *
     * @var ObjectIdentifier[]
     */
    private $Objects;

    /**
     * Element to enable quiet mode for the request. When you add this element, you must set its value to true.
     *
     * @var bool|null
     */
    private $Quiet;

    /**
     * @param array{
     *   Objects: \AsyncAws\S3\Input\ObjectIdentifier[],
     *   Quiet?: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Objects = array_map(function ($item) { return ObjectIdentifier::create($item); }, $input['Objects'] ?? []);
        $this->Quiet = $input['Quiet'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getObjects(): array
    {
        return $this->Objects;
    }

    public function getQuiet(): ?bool
    {
        return $this->Quiet;
    }

    public function setObjects(array $value): self
    {
        $this->Objects = $value;

        return $this;
    }

    public function setQuiet(?bool $value): self
    {
        $this->Quiet = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['Objects'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        foreach ($this->Objects as $item) {
            $item->validate();
        }
    }
}
