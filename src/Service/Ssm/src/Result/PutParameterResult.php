<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ssm\Enum\ParameterTier;

class PutParameterResult extends Result
{
    /**
     * The new version number of a parameter. If you edit a parameter value, Parameter Store automatically creates a new
     * version and assigns this new version a unique ID. You can reference a parameter version ID in API operations or in
     * Systems Manager documents (SSM documents). By default, if you don't specify a specific version, the system returns
     * the latest parameter value when a parameter is called.
     *
     * @var int|null
     */
    private $version;

    /**
     * The tier assigned to the parameter.
     *
     * @var ParameterTier::*|null
     */
    private $tier;

    /**
     * @return ParameterTier::*|null
     */
    public function getTier(): ?string
    {
        $this->initialize();

        return $this->tier;
    }

    public function getVersion(): ?int
    {
        $this->initialize();

        return $this->version;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->version = isset($data['Version']) ? (int) $data['Version'] : null;
        $this->tier = isset($data['Tier']) ? (string) $data['Tier'] : null;
    }
}
