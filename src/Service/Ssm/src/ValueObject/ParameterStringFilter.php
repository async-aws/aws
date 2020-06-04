<?php

namespace AsyncAws\Ssm\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ParameterStringFilter
{
    /**
     * The name of the filter.
     */
    private $Key;

    /**
     * For all filters used with DescribeParameters, valid options include `Equals` and `BeginsWith`. The `Name` filter
     * additionally supports the `Contains` option. (Exception: For filters using the key `Path`, valid options include
     * `Recursive` and `OneLevel`.).
     */
    private $Option;

    /**
     * The value you want to search for.
     */
    private $Values;

    /**
     * @param array{
     *   Key: string,
     *   Option?: null|string,
     *   Values?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'] ?? null;
        $this->Option = $input['Option'] ?? null;
        $this->Values = $input['Values'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->Key;
    }

    public function getOption(): ?string
    {
        return $this->Option;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->Values ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Key'] = $v;
        if (null !== $v = $this->Option) {
            $payload['Option'] = $v;
        }
        if (null !== $v = $this->Values) {
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
