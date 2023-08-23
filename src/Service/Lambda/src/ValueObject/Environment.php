<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * A function's environment variable settings. You can use environment variables to adjust your function's behavior
 * without updating code. An environment variable is a pair of strings that are stored in a function's version-specific
 * configuration.
 */
final class Environment
{
    /**
     * Environment variable key-value pairs. For more information, see Using Lambda environment variables [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-envvars.html
     *
     * @var array<string, string>|null
     */
    private $variables;

    /**
     * @param array{
     *   Variables?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->variables = $input['Variables'] ?? null;
    }

    /**
     * @param array{
     *   Variables?: null|array<string, string>,
     * }|Environment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getVariables(): array
    {
        return $this->variables ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->variables) {
            if (empty($v)) {
                $payload['Variables'] = new \stdClass();
            } else {
                $payload['Variables'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Variables'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
