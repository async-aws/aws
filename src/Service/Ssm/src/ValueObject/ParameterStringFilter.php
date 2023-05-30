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
     *
     * The `ParameterStringFilter` object is used by the DescribeParameters and GetParametersByPath API operations. However,
     * not all of the pattern values listed for `Key` can be used with both operations.
     *
     * For `DescribeParameters`, all of the listed patterns are valid except `Label`.
     *
     * For `GetParametersByPath`, the following patterns listed for `Key` aren't valid: `tag`, `DataType`, `Name`, `Path`,
     * and `Tier`.
     *
     * For examples of Amazon Web Services CLI commands demonstrating valid parameter filter constructions, see Searching
     * for Systems Manager parameters [^1] in the *Amazon Web Services Systems Manager User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/systems-manager/latest/userguide/parameter-search.html
     */
    private $key;

    /**
     * For all filters used with DescribeParameters, valid options include `Equals` and `BeginsWith`. The `Name` filter
     * additionally supports the `Contains` option. (Exception: For filters using the key `Path`, valid options include
     * `Recursive` and `OneLevel`.).
     *
     * For filters used with GetParametersByPath, valid options include `Equals` and `BeginsWith`. (Exception: For filters
     * using `Label` as the Key name, the only valid option is `Equals`.)
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
