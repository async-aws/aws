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
        $this->invalidation = new Invalidation([
            'Id' => (string) $data->Id,
            'Status' => (string) $data->Status,
            'CreateTime' => new \DateTimeImmutable((string) $data->CreateTime),
            'InvalidationBatch' => new InvalidationBatch([
                'Paths' => new Paths([
                    'Quantity' => (int) (string) $data->InvalidationBatch->Paths->Quantity,
                    'Items' => !$data->InvalidationBatch->Paths->Items ? null : $this->populateResultPathList($data->InvalidationBatch->Paths->Items),
                ]),
                'CallerReference' => (string) $data->InvalidationBatch->CallerReference,
            ]),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultPathList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Path as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
