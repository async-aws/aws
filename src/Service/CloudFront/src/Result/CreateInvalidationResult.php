<?php

namespace AsyncAws\CloudFront\Result;

use AsyncAws\CloudFront\ValueObject\Invalidation;
use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\CloudFront\ValueObject\Paths;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The returned result of the corresponding request.
 */
class CreateInvalidationResult extends Result
{
    /**
     * The fully qualified URI of the distribution and invalidation batch request, including the `Invalidation ID`.
     *
     * @var string|null
     */
    private $location;

    /**
     * The invalidation's information.
     *
     * @var Invalidation|null
     */
    private $invalidation;

    public function getInvalidation(): ?Invalidation
    {
        $this->initialize();

        return $this->invalidation;
    }

    public function getLocation(): ?string
    {
        $this->initialize();

        return $this->location;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->location = $headers['location'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->invalidation = $this->populateResultInvalidation($data);
    }

    private function populateResultInvalidation(\SimpleXMLElement $xml): Invalidation
    {
        return new Invalidation([
            'Id' => (string) $xml->Id,
            'Status' => (string) $xml->Status,
            'CreateTime' => new \DateTimeImmutable((string) $xml->CreateTime),
            'InvalidationBatch' => $this->populateResultInvalidationBatch($xml->InvalidationBatch),
        ]);
    }

    private function populateResultInvalidationBatch(\SimpleXMLElement $xml): InvalidationBatch
    {
        return new InvalidationBatch([
            'Paths' => $this->populateResultPaths($xml->Paths),
            'CallerReference' => (string) $xml->CallerReference,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultPathList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Path as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }

    private function populateResultPaths(\SimpleXMLElement $xml): Paths
    {
        return new Paths([
            'Quantity' => (int) (string) $xml->Quantity,
            'Items' => (0 === ($v = $xml->Items)->count()) ? null : $this->populateResultPathList($v),
        ]);
    }
}
