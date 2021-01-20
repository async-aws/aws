<?php

namespace AsyncAws\Ssm\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * One or more filters. Use a filter to return a more specific list of results.
 */
final class ParameterStringFilter
{
    /**
     * The name of the filter.
     */
    private $key;

    /**
     * For all filters used with DescribeParameters, valid options include `Equals` and `BeginsWith`. The `Name` filter
     * additionally supports the `Contains` option. (Exception: For filters using the key `Path`, valid options include
     * `Recursive` and `OneLevel`.).
     */
    private $option;

    /**
     * The value you want to search for.
     */
    private $values;

    /**
     * @param array{
     *   Key: string,
     *   Option?: null|string,
     *   Values?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->option = $input['Option'] ?? null;
        $this->values = $input['Values'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getOption(): ?string
    {
        return $this->option;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Key'] = $v;
        if (null !== $v = $this->option) {
            $payload['Option'] = $v;
        }
        if (null !== $v = $this->values) {
            $index = -1;
            $payload['Values'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Values'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
