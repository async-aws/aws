<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The new HTTP endpoint configuration.
 */
final class HttpDataSourceConfig
{
    /**
     * The HTTP URL endpoint. You can either specify the domain name or IP, and port combination, and the URL scheme must be
     * HTTP or HTTPS. If the port is not specified, AppSync uses the default port 80 for the HTTP endpoint and port 443 for
     * HTTPS endpoints.
     */
    private $endpoint;

    /**
     * The authorization config in case the HTTP endpoint requires authorization.
     */
    private $authorizationConfig;

    /**
     * @param array{
     *   endpoint?: null|string,
     *   authorizationConfig?: null|AuthorizationConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->endpoint = $input['endpoint'] ?? null;
        $this->authorizationConfig = isset($input['authorizationConfig']) ? AuthorizationConfig::create($input['authorizationConfig']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAuthorizationConfig(): ?AuthorizationConfig
    {
        return $this->authorizationConfig;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->endpoint) {
            $payload['endpoint'] = $v;
        }
        if (null !== $v = $this->authorizationConfig) {
            $payload['authorizationConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
