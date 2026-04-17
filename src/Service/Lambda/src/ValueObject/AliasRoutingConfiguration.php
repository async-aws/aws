<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The traffic-shifting [^1] configuration of a Lambda function alias.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-traffic-shifting-using-aliases.html
 */
final class AliasRoutingConfiguration
{
    /**
     * The second version, and the percentage of traffic that's routed to it.
     *
     * @var array<string, float>|null
     */
    private $additionalVersionWeights;

    /**
     * @param array{
     *   AdditionalVersionWeights?: array<string, float>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->additionalVersionWeights = $input['AdditionalVersionWeights'] ?? null;
    }

    /**
     * @param array{
     *   AdditionalVersionWeights?: array<string, float>|null,
     * }|AliasRoutingConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, float>
     */
    public function getAdditionalVersionWeights(): array
    {
        return $this->additionalVersionWeights ?? [];
    }
}
