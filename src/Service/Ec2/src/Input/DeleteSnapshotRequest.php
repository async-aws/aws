<?php

namespace AsyncAws\Ec2\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteSnapshotRequest extends Input
{
    /**
     * The ID of the EBS snapshot.
     *
     * @required
     *
     * @var string|null
     */
    private $snapshotId;

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
     *   SnapshotId?: string,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->snapshotId = $input['SnapshotId'] ?? null;
        $this->dryRun = $input['DryRun'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SnapshotId?: string,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * }|DeleteSnapshotRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDryRun(): ?bool
    {
        return $this->dryRun;
    }

    public function getSnapshotId(): ?string
    {
        return $this->snapshotId;
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
        $body = http_build_query(['Action' => 'DeleteSnapshot', 'Version' => '2016-11-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDryRun(?bool $value): self
    {
        $this->dryRun = $value;

        return $this;
    }

    public function setSnapshotId(?string $value): self
    {
        $this->snapshotId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->snapshotId) {
            throw new InvalidArgument(\sprintf('Missing parameter "SnapshotId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SnapshotId'] = $v;
        if (null !== $v = $this->dryRun) {
            $payload['DryRun'] = $v ? 'true' : 'false';
        }

        return $payload;
    }
}
