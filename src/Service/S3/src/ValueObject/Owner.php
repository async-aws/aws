<?php

namespace AsyncAws\S3\ValueObject;

/**
 * ! End of support notice: Beginning October 1, 2025, Amazon S3 will stop returning `DisplayName`. Update your
 * ! applications to use canonical IDs (unique identifier for Amazon Web Services accounts), Amazon Web Services account
 * ! ID (12 digit identifier) or IAM ARNs (full resource naming) as a direct replacement of `DisplayName`.
 * !
 * ! This change affects the following Amazon Web Services Regions: US East (N. Virginia) Region, US West (N.
 * ! California) Region, US West (Oregon) Region, Asia Pacific (Singapore) Region, Asia Pacific (Sydney) Region, Asia
 * ! Pacific (Tokyo) Region, Europe (Ireland) Region, and South America (São Paulo) Region.
 *
 * Container for the owner's display name and ID.
 */
final class Owner
{
    /**
     * Container for the display name of the owner. This value is only supported in the following Amazon Web Services
     * Regions:
     *
     * - US East (N. Virginia)
     * - US West (N. California)
     * - US West (Oregon)
     * - Asia Pacific (Singapore)
     * - Asia Pacific (Sydney)
     * - Asia Pacific (Tokyo)
     * - Europe (Ireland)
     * - South America (São Paulo)
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $displayName;

    /**
     * Container for the ID of the owner.
     *
     * @var string|null
     */
    private $id;

    /**
     * @param array{
     *   DisplayName?: null|string,
     *   ID?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->displayName = $input['DisplayName'] ?? null;
        $this->id = $input['ID'] ?? null;
    }

    /**
     * @param array{
     *   DisplayName?: null|string,
     *   ID?: null|string,
     * }|Owner $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->displayName) {
            $node->appendChild($document->createElement('DisplayName', $v));
        }
        if (null !== $v = $this->id) {
            $node->appendChild($document->createElement('ID', $v));
        }
    }
}
