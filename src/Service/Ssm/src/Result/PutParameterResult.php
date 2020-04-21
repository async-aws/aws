<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ssm\Enum\ParameterTier;

class PutParameterResult extends Result
{
    /**
     * The new version number of a parameter. If you edit a parameter value, Parameter Store automatically creates a new
     * version and assigns this new version a unique ID. You can reference a parameter version ID in API actions or in
     * Systems Manager documents (SSM documents). By default, if you don't specify a specific version, the system returns
     * the latest parameter value when a parameter is called.
     */
    private $Version;

    /**
     * The tier assigned to the parameter.
     */
    private $Tier;

    /**
     * @return ParameterTier::*|null
     */
    public function getTier(): ?string
    {
        $this->initialize();

        return $this->Tier;
    }

    public function getVersion(): ?string
    {
        $this->initialize();

        return $this->Version;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->Version = isset($data['Version']) ? (string) $data['Version'] : null;
        $this->Tier = isset($data['Tier']) ? (string) $data['Tier'] : null;
    }
}
