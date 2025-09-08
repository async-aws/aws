<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * If a health check or hosted zone was created by another service, `LinkedService` is a complex type that describes the
 * service that created the resource. When a resource is created by another service, you can't edit or delete it using
 * Amazon Route 53.
 */
final class LinkedService
{
    /**
     * If the health check or hosted zone was created by another service, the service that created the resource. When a
     * resource is created by another service, you can't edit or delete it using Amazon Route 53.
     *
     * @var string|null
     */
    private $servicePrincipal;

    /**
     * If the health check or hosted zone was created by another service, an optional description that can be provided by
     * the other service. When a resource is created by another service, you can't edit or delete it using Amazon Route 53.
     *
     * @var string|null
     */
    private $description;

    /**
     * @param array{
     *   ServicePrincipal?: string|null,
     *   Description?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->servicePrincipal = $input['ServicePrincipal'] ?? null;
        $this->description = $input['Description'] ?? null;
    }

    /**
     * @param array{
     *   ServicePrincipal?: string|null,
     *   Description?: string|null,
     * }|LinkedService $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getServicePrincipal(): ?string
    {
        return $this->servicePrincipal;
    }
}
