<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The number of in-progress human reviews you have has exceeded the number allowed.
 */
final class HumanLoopQuotaExceededException extends ClientException
{
    /**
     * The resource type.
     *
     * @var string|null
     */
    private $resourceType;

    /**
     * The quota code.
     *
     * @var string|null
     */
    private $quotaCode;

    /**
     * The service code.
     *
     * @var string|null
     */
    private $serviceCode;

    public function getQuotaCode(): ?string
    {
        return $this->quotaCode;
    }

    public function getResourceType(): ?string
    {
        return $this->resourceType;
    }

    public function getServiceCode(): ?string
    {
        return $this->serviceCode;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->resourceType = isset($data['ResourceType']) ? (string) $data['ResourceType'] : null;
        $this->quotaCode = isset($data['QuotaCode']) ? (string) $data['QuotaCode'] : null;
        $this->serviceCode = isset($data['ServiceCode']) ? (string) $data['ServiceCode'] : null;
    }
}
