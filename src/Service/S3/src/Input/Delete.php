<?php

namespace AsyncAws\S3\Input;

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

    /**
     * @return ObjectIdentifier[]
     */
    public function getObjects(): array
    {
        return $this->Objects;
    }

    public function getQuiet(): ?bool
    {
        return $this->Quiet;
    }

    /**
     * @param ObjectIdentifier[] $value
     */
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
        foreach ($this->Objects as $item) {
            $item->validate();
        }
    }
}
