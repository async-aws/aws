<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class AddLayerVersionPermissionRequest
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
    public function requestBody(): string
    {
        $payload = [];

        $payload['StatementId'] = $this->StatementId;
        $payload['Action'] = $this->Action;
        $payload['Principal'] = $this->Principal;
        if (null !== $v = $this->OrganizationId) {
            $payload['OrganizationId'] = $v;
        }

        return json_encode($payload);
    }

    /**
     * @internal
     */
    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/json'];

        return $headers;
    }

    /**
     * @internal
     */
    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->RevisionId) {
            $query['RevisionId'] = $this->RevisionId;
        }

        return $query;
    }

    /**
     * @internal
     */
    public function requestUri(): string
    {
        $uri = [];
        $uri['LayerName'] = $this->LayerName ?? '';
        $uri['VersionNumber'] = $this->VersionNumber ?? '';

        return "/2018-10-31/layers/{$uri['LayerName']}/versions/{$uri['VersionNumber']}/policy";
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

    public function validate(): void
    {
        if (null === $this->LayerName) {
            throw new InvalidArgument(sprintf('Missing parameter "LayerName" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->VersionNumber) {
            throw new InvalidArgument(sprintf('Missing parameter "VersionNumber" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->StatementId) {
            throw new InvalidArgument(sprintf('Missing parameter "StatementId" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Action) {
            throw new InvalidArgument(sprintf('Missing parameter "Action" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Principal) {
            throw new InvalidArgument(sprintf('Missing parameter "Principal" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
