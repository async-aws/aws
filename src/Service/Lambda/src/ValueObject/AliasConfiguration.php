<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Provides configuration information about a Lambda function alias [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-aliases.html
 */
final class AliasConfiguration
{
    /**
     * The Amazon Resource Name (ARN) of the alias.
     *
     * @var string|null
     */
    private $aliasArn;

    /**
     * The name of the alias.
     *
     * @var string|null
     */
    private $name;

    /**
     * The function version that the alias invokes.
     *
     * @var string|null
     */
    private $functionVersion;

    /**
     * A description of the alias.
     *
     * @var string|null
     */
    private $description;

    /**
     * The routing configuration [^1] of the alias.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-traffic-shifting-using-aliases.html
     *
     * @var AliasRoutingConfiguration|null
     */
    private $routingConfig;

    /**
     * A unique identifier that changes when you update the alias.
     *
     * @var string|null
     */
    private $revisionId;

    /**
     * @param array{
     *   AliasArn?: string|null,
     *   Name?: string|null,
     *   FunctionVersion?: string|null,
     *   Description?: string|null,
     *   RoutingConfig?: AliasRoutingConfiguration|array|null,
     *   RevisionId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->aliasArn = $input['AliasArn'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->functionVersion = $input['FunctionVersion'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->routingConfig = isset($input['RoutingConfig']) ? AliasRoutingConfiguration::create($input['RoutingConfig']) : null;
        $this->revisionId = $input['RevisionId'] ?? null;
    }

    /**
     * @param array{
     *   AliasArn?: string|null,
     *   Name?: string|null,
     *   FunctionVersion?: string|null,
     *   Description?: string|null,
     *   RoutingConfig?: AliasRoutingConfiguration|array|null,
     *   RevisionId?: string|null,
     * }|AliasConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAliasArn(): ?string
    {
        return $this->aliasArn;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getFunctionVersion(): ?string
    {
        return $this->functionVersion;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRevisionId(): ?string
    {
        return $this->revisionId;
    }

    public function getRoutingConfig(): ?AliasRoutingConfiguration
    {
        return $this->routingConfig;
    }
}
