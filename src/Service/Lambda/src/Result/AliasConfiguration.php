<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\ValueObject\AliasRoutingConfiguration;

/**
 * Provides configuration information about a Lambda function alias [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-aliases.html
 */
class AliasConfiguration extends Result
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

    public function getAliasArn(): ?string
    {
        $this->initialize();

        return $this->aliasArn;
    }

    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->description;
    }

    public function getFunctionVersion(): ?string
    {
        $this->initialize();

        return $this->functionVersion;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    public function getRevisionId(): ?string
    {
        $this->initialize();

        return $this->revisionId;
    }

    public function getRoutingConfig(): ?AliasRoutingConfiguration
    {
        $this->initialize();

        return $this->routingConfig;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->aliasArn = isset($data['AliasArn']) ? (string) $data['AliasArn'] : null;
        $this->name = isset($data['Name']) ? (string) $data['Name'] : null;
        $this->functionVersion = isset($data['FunctionVersion']) ? (string) $data['FunctionVersion'] : null;
        $this->description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->routingConfig = empty($data['RoutingConfig']) ? null : $this->populateResultAliasRoutingConfiguration($data['RoutingConfig']);
        $this->revisionId = isset($data['RevisionId']) ? (string) $data['RevisionId'] : null;
    }

    /**
     * @return array<string, float>
     */
    private function populateResultAdditionalVersionWeights(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (float) $value;
        }

        return $items;
    }

    private function populateResultAliasRoutingConfiguration(array $json): AliasRoutingConfiguration
    {
        return new AliasRoutingConfiguration([
            'AdditionalVersionWeights' => !isset($json['AdditionalVersionWeights']) ? null : $this->populateResultAdditionalVersionWeights($json['AdditionalVersionWeights']),
        ]);
    }
}
