<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A complex type that contains general information about the hosted zone.
 */
final class HostedZone
{
    /**
     * The ID that Amazon Route 53 assigned to the hosted zone when you created it.
     *
     * @var string
     */
    private $id;

    /**
     * The name of the domain. For public hosted zones, this is the name that you have registered with your DNS registrar.
     *
     * For information about how to specify characters other than `a-z`, `0-9`, and `-` (hyphen) and how to specify
     * internationalized domain names, see CreateHostedZone [^1].
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateHostedZone.html
     *
     * @var string
     */
    private $name;

    /**
     * The value that you specified for `CallerReference` when you created the hosted zone.
     *
     * @var string
     */
    private $callerReference;

    /**
     * A complex type that includes the `Comment` and `PrivateZone` elements. If you omitted the `HostedZoneConfig` and
     * `Comment` elements from the request, the `Config` and `Comment` elements don't appear in the response.
     *
     * @var HostedZoneConfig|null
     */
    private $config;

    /**
     * The number of resource record sets in the hosted zone.
     *
     * @var int|null
     */
    private $resourceRecordSetCount;

    /**
     * If the hosted zone was created by another service, the service that created the hosted zone. When a hosted zone is
     * created by another service, you can't edit or delete it using Route 53.
     *
     * @var LinkedService|null
     */
    private $linkedService;

    /**
     * @param array{
     *   Id: string,
     *   Name: string,
     *   CallerReference: string,
     *   Config?: null|HostedZoneConfig|array,
     *   ResourceRecordSetCount?: null|int,
     *   LinkedService?: null|LinkedService|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->callerReference = $input['CallerReference'] ?? $this->throwException(new InvalidArgument('Missing required field "CallerReference".'));
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
     *   ResourceRecordSetCount?: null|int,
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

    public function getResourceRecordSetCount(): ?int
    {
        return $this->resourceRecordSetCount;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
