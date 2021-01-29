<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AddLayerVersionPermissionRequest extends Input
{
    /**
     * The name or Amazon Resource Name (ARN) of the layer.
     *
     * @required
     *
     * @var string|null
     */
    private $layerName;

    /**
     * The version number.
     *
     * @required
     *
     * @var string|null
     */
    private $versionNumber;

    /**
     * An identifier that distinguishes the policy from others on the same layer version.
     *
     * @required
     *
     * @var string|null
     */
    private $statementId;

    /**
     * The API action that grants access to the layer. For example, `lambda:GetLayerVersion`.
     *
     * @required
     *
     * @var string|null
     */
    private $action;

    /**
     * An account ID, or `*` to grant permission to all AWS accounts.
     *
     * @required
     *
     * @var string|null
     */
    private $principal;

    /**
     * With the principal set to `*`, grant permission to all accounts in the specified organization.
     *
     * @var string|null
     */
    private $organizationId;

    /**
     * Only update the policy if the revision ID matches the ID specified. Use this option to avoid modifying a policy that
     * has changed since you last read it.
     *
     * @var string|null
     */
    private $revisionId;

    /**
     * @param array{
     *   LayerName?: string,
     *   VersionNumber?: string,
     *   StatementId?: string,
     *   Action?: string,
     *   Principal?: string,
     *   OrganizationId?: string,
     *   RevisionId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->layerName = $input['LayerName'] ?? null;
        $this->versionNumber = $input['VersionNumber'] ?? null;
        $this->statementId = $input['StatementId'] ?? null;
        $this->action = $input['Action'] ?? null;
        $this->principal = $input['Principal'] ?? null;
        $this->organizationId = $input['OrganizationId'] ?? null;
        $this->revisionId = $input['RevisionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getLayerName(): ?string
    {
        return $this->layerName;
    }

    public function getOrganizationId(): ?string
    {
        return $this->organizationId;
    }

    public function getPrincipal(): ?string
    {
        return $this->principal;
    }

    public function getRevisionId(): ?string
    {
        return $this->revisionId;
    }

    public function getStatementId(): ?string
    {
        return $this->statementId;
    }

    public function getVersionNumber(): ?string
    {
        return $this->versionNumber;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];
        if (null !== $this->revisionId) {
            $query['RevisionId'] = $this->revisionId;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->layerName) {
            throw new InvalidArgument(sprintf('Missing parameter "LayerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['LayerName'] = $v;
        if (null === $v = $this->versionNumber) {
            throw new InvalidArgument(sprintf('Missing parameter "VersionNumber" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['VersionNumber'] = $v;
        $uriString = '/2018-10-31/layers/' . rawurlencode($uri['LayerName']) . '/versions/' . rawurlencode($uri['VersionNumber']) . '/policy';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAction(?string $value): self
    {
        $this->action = $value;

        return $this;
    }

    public function setLayerName(?string $value): self
    {
        $this->layerName = $value;

        return $this;
    }

    public function setOrganizationId(?string $value): self
    {
        $this->organizationId = $value;

        return $this;
    }

    public function setPrincipal(?string $value): self
    {
        $this->principal = $value;

        return $this;
    }

    public function setRevisionId(?string $value): self
    {
        $this->revisionId = $value;

        return $this;
    }

    public function setStatementId(?string $value): self
    {
        $this->statementId = $value;

        return $this;
    }

    public function setVersionNumber(?string $value): self
    {
        $this->versionNumber = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->statementId) {
            throw new InvalidArgument(sprintf('Missing parameter "StatementId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StatementId'] = $v;
        if (null === $v = $this->action) {
            throw new InvalidArgument(sprintf('Missing parameter "Action" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Action'] = $v;
        if (null === $v = $this->principal) {
            throw new InvalidArgument(sprintf('Missing parameter "Principal" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Principal'] = $v;
        if (null !== $v = $this->organizationId) {
            $payload['OrganizationId'] = $v;
        }

        return $payload;
    }
}
