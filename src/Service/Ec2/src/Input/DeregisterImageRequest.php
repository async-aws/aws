<?php

namespace AsyncAws\Ec2\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Contains the parameters for DeregisterImage.
 */
final class DeregisterImageRequest extends Input
{
    /**
     * The ID of the AMI.
     *
     * @required
     *
     * @var string|null
     */
    private $imageId;

    /**
     * Specifies whether to delete the snapshots associated with the AMI during deregistration.
     *
     * > If a snapshot is associated with multiple AMIs, it is not deleted, regardless of this setting.
     *
     * Default: The snapshots are not deleted.
     *
     * @var bool|null
     */
    private $deleteAssociatedSnapshots;

    /**
     * Checks whether you have the required permissions for the action, without actually making the request, and provides an
     * error response. If you have the required permissions, the error response is `DryRunOperation`. Otherwise, it is
     * `UnauthorizedOperation`.
     *
     * @var bool|null
     */
    private $dryRun;

    /**
     * @param array{
     *   ImageId?: string,
     *   DeleteAssociatedSnapshots?: bool|null,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->imageId = $input['ImageId'] ?? null;
        $this->deleteAssociatedSnapshots = $input['DeleteAssociatedSnapshots'] ?? null;
        $this->dryRun = $input['DryRun'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ImageId?: string,
     *   DeleteAssociatedSnapshots?: bool|null,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * }|DeregisterImageRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeleteAssociatedSnapshots(): ?bool
    {
        return $this->deleteAssociatedSnapshots;
    }

    public function getDryRun(): ?bool
    {
        return $this->dryRun;
    }

    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'DeregisterImage', 'Version' => '2016-11-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDeleteAssociatedSnapshots(?bool $value): self
    {
        $this->deleteAssociatedSnapshots = $value;

        return $this;
    }

    public function setDryRun(?bool $value): self
    {
        $this->dryRun = $value;

        return $this;
    }

    public function setImageId(?string $value): self
    {
        $this->imageId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->imageId) {
            throw new InvalidArgument(\sprintf('Missing parameter "ImageId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ImageId'] = $v;
        if (null !== $v = $this->deleteAssociatedSnapshots) {
            $payload['DeleteAssociatedSnapshots'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->dryRun) {
            $payload['DryRun'] = $v ? 'true' : 'false';
        }

        return $payload;
    }
}
