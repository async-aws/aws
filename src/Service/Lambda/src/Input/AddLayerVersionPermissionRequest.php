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
    private $LayerName;

    /**
     * The version number.
     *
     * @required
     *
     * @var string|null
     */
    private $VersionNumber;

    /**
     * An identifier that distinguishes the policy from others on the same layer version.
     *
     * @required
     *
     * @var string|null
     */
    private $StatementId;

    /**
     * The API action that grants access to the layer. For example, `lambda:GetLayerVersion`.
     *
     * @required
     *
     * @var string|null
     */
    private $Action;

    /**
     * An account ID, or `*` to grant permission to all AWS accounts.
     *
     * @required
     *
     * @var string|null
     */
    private $Principal;

    /**
     * With the principal set to `*`, grant permission to all accounts in the specified organization.
     *
     * @var string|null
     */
    private $OrganizationId;

    /**
     * Only update the policy if the revision ID matches the ID specified. Use this option to avoid modifying a policy that
     * has changed since you last read it.
     *
     * @var string|null
     */
    private $RevisionId;

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
        $this->LayerName = $input['LayerName'] ?? null;
        $this->VersionNumber = $input['VersionNumber'] ?? null;
        $this->StatementId = $input['StatementId'] ?? null;
        $this->Action = $input['Action'] ?? null;
        $this->Principal = $input['Principal'] ?? null;
        $this->OrganizationId = $input['OrganizationId'] ?? null;
        $this->RevisionId = $input['RevisionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAction(): ?string
    {
        return $this->Action;
    }

    public function getLayerName(): ?string
    {
        return $this->LayerName;
    }

    public function getOrganizationId(): ?string
    {
        return $this->OrganizationId;
    }

    public function getPrincipal(): ?string
    {
        return $this->Principal;
    }

    public function getRevisionId(): ?string
    {
        return $this->RevisionId;
    }

    public function getStatementId(): ?string
    {
        return $this->StatementId;
    }

    public function getVersionNumber(): ?string
    {
        return $this->VersionNumber;
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
        if (null !== $this->RevisionId) {
            $query['RevisionId'] = $this->RevisionId;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->LayerName) {
            throw new InvalidArgument(sprintf('Missing parameter "LayerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['LayerName'] = $v;
        if (null === $v = $this->VersionNumber) {
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
        $this->Action = $value;

        return $this;
    }

    public function setLayerName(?string $value): self
    {
        $this->LayerName = $value;

        return $this;
    }

    public function setOrganizationId(?string $value): self
    {
        $this->OrganizationId = $value;

        return $this;
    }

    public function setPrincipal(?string $value): self
    {
        $this->Principal = $value;

        return $this;
    }

    public function setRevisionId(?string $value): self
    {
        $this->RevisionId = $value;

        return $this;
    }

    public function setStatementId(?string $value): self
    {
        $this->StatementId = $value;

        return $this;
    }

    public function setVersionNumber(?string $value): self
    {
        $this->VersionNumber = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->StatementId) {
            throw new InvalidArgument(sprintf('Missing parameter "StatementId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StatementId'] = $v;
        if (null === $v = $this->Action) {
            throw new InvalidArgument(sprintf('Missing parameter "Action" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Action'] = $v;
        if (null === $v = $this->Principal) {
            throw new InvalidArgument(sprintf('Missing parameter "Principal" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Principal'] = $v;
        if (null !== $v = $this->OrganizationId) {
            $payload['OrganizationId'] = $v;
        }

        return $payload;
    }
}
