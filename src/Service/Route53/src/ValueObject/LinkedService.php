<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * If the hosted zone was created by another service, the service that created the hosted zone. When a hosted zone is
 * created by another service, you can't edit or delete it using Route 53.
 */
final class LinkedService
{
    /**
     * If the health check or hosted zone was created by another service, the service that created the resource. When a
     * resource is created by another service, you can't edit or delete it using Amazon Route 53.
     */
    private $servicePrincipal;

    /**
     * If the health check or hosted zone was created by another service, an optional description that can be provided by
     * the other service. When a resource is created by another service, you can't edit or delete it using Amazon Route 53.
     */
    private $description;

    /**
     * @param array{
     *   ServicePrincipal?: null|string,
     *   Description?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->servicePrincipal = $input['ServicePrincipal'] ?? null;
        $this->description = $input['Description'] ?? null;
    }

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
