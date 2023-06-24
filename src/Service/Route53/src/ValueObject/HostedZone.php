<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * A complex type that contains general information about the hosted zone.
 */
final class HostedZone
{
    /**
     * The ID that Amazon Route 53 assigned to the hosted zone when you created it.
     */
    private $id;

    /**
     * The name of the domain. For public hosted zones, this is the name that you have registered with your DNS registrar.
     *
     * For information about how to specify characters other than `a-z`, `0-9`, and `-` (hyphen) and how to specify
     * internationalized domain names, see CreateHostedZone [^1].
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateHostedZone.html
     */
    private $name;

    /**
     * The value that you specified for `CallerReference` when you created the hosted zone.
     */
    private $callerReference;

    /**
     * A complex type that includes the `Comment` and `PrivateZone` elements. If you omitted the `HostedZoneConfig` and
     * `Comment` elements from the request, the `Config` and `Comment` elements don't appear in the response.
     */
    private $config;

    /**
     * The number of resource record sets in the hosted zone.
     */
    private $resourceRecordSetCount;

    /**
     * If the hosted zone was created by another service, the service that created the hosted zone. When a hosted zone is
     * created by another service, you can't edit or delete it using Route 53.
     */
    private $linkedService;

    /**
     * @param array{
     *   Id: string,
     *   Name: string,
     *   CallerReference: string,
     *   Config?: null|HostedZoneConfig|array,
     *   ResourceRecordSetCount?: null|string,
     *   LinkedService?: null|LinkedService|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->callerReference = $input['CallerReference'] ?? null;
        $this->config = isset($input['Config']) ? HostedZoneConfig::create($input['Config']) : null;
        $this->resourceRecordSetCount = $input['ResourceRecordSetCount'] ?? null;
        $this->linkedService = isset($input['LinkedService']) ? LinkedService::create($input['LinkedService']) : null;
    }

    /**
     * @param array{
     *   Id: string,
     *   Name: string,
     *   CallerReference: string,
     *   Config?: null|HostedZoneConfig|array,
     *   ResourceRecordSetCount?: null|string,
     *   LinkedService?: null|LinkedService|array,
     * }|HostedZone $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCallerReference(): string
    {
        return $this->callerReference;
    }

    public function getConfig(): ?HostedZoneConfig
    {
        return $this->config;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLinkedService(): ?LinkedService
    {
        return $this->linkedService;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResourceRecordSetCount(): ?string
    {
        return $this->resourceRecordSetCount;
    }
}
