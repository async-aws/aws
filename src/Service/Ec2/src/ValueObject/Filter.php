<?php

namespace AsyncAws\Ec2\ValueObject;

/**
 * A filter name and value pair that is used to return a more specific list of results from a describe operation.
 * Filters can be used to match a set of resources by specific criteria, such as tags, attributes, or IDs.
 *
 * If you specify multiple filters, the filters are joined with an `AND`, and the request returns only results that
 * match all of the specified filters.
 *
 * For more information, see List and filter using the CLI and API [^1] in the *Amazon EC2 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/Using_Filtering.html#Filtering_Resources_CLI
 */
final class Filter
{
    /**
     * The name of the filter. Filter names are case-sensitive.
     *
     * @var string|null
     */
    private $name;

    /**
     * The filter values. Filter values are case-sensitive. If you specify multiple values for a filter, the values are
     * joined with an `OR`, and the request returns all results that match any of the specified values.
     *
     * @var string[]|null
     */
    private $values;

    /**
     * @param array{
     *   Name?: string|null,
     *   Values?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->values = $input['Values'] ?? null;
    }

    /**
     * @param array{
     *   Name?: string|null,
     *   Values?: string[]|null,
     * }|Filter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
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
        if (null !== $v = $this->name) {
            $payload['Name'] = $v;
        }
        if (null !== $v = $this->values) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["Value.$index"] = $mapValue;
            }
        }

        return $payload;
    }
}
