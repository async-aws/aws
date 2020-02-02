<?php

namespace AsyncAws\Core\Sts\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class AssumeRoleRequest
{
    /**
     * @required
     *
     * @var string|null
     */
    private $RoleArn;
    /**
     * @required
     *
     * @var string|null
     */
    private $RoleSessionName;
    /**
     * @var PolicyDescriptorType[]
     */
    private $PolicyArns;
    /**
     * @var string|null
     */
    private $Policy;
    /**
     * @var int|null
     */
    private $DurationSeconds;
    /**
     * @var Tag[]
     */
    private $Tags;
    /**
     * @var string[]
     */
    private $TransitiveTagKeys;
    /**
     * @var string|null
     */
    private $ExternalId;
    /**
     * @var string|null
     */
    private $SerialNumber;
    /**
     * @var string|null
     */
    private $TokenCode;

    /**
     * @param array{
     *   RoleArn: string,
     *   RoleSessionName: string,
     *   PolicyArns?: \AsyncAws\Core\Sts\Input\PolicyDescriptorType[],
     *   Policy?: string,
     *   DurationSeconds?: int,
     *   Tags?: \AsyncAws\Core\Sts\Input\Tag[],
     *   TransitiveTagKeys?: string[],
     *   ExternalId?: string,
     *   SerialNumber?: string,
     *   TokenCode?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->RoleArn = $input['RoleArn'] ?? null;
        $this->RoleSessionName = $input['RoleSessionName'] ?? null;
        $this->PolicyArns = array_map(function ($item) { return PolicyDescriptorType::create($item); }, $input['PolicyArns'] ?? []);
        $this->Policy = $input['Policy'] ?? null;
        $this->DurationSeconds = $input['DurationSeconds'] ?? null;
        $this->Tags = array_map(function ($item) { return Tag::create($item); }, $input['Tags'] ?? []);
        $this->TransitiveTagKeys = $input['TransitiveTagKeys'] ?? [];
        $this->ExternalId = $input['ExternalId'] ?? null;
        $this->SerialNumber = $input['SerialNumber'] ?? null;
        $this->TokenCode = $input['TokenCode'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDurationSeconds(): ?int
    {
        return $this->DurationSeconds;
    }

    public function getExternalId(): ?string
    {
        return $this->ExternalId;
    }

    public function getPolicy(): ?string
    {
        return $this->Policy;
    }

    public function getPolicyArns(): array
    {
        return $this->PolicyArns;
    }

    public function getRoleArn(): ?string
    {
        return $this->RoleArn;
    }

    public function getRoleSessionName(): ?string
    {
        return $this->RoleSessionName;
    }

    public function getSerialNumber(): ?string
    {
        return $this->SerialNumber;
    }

    public function getTags(): array
    {
        return $this->Tags;
    }

    public function getTokenCode(): ?string
    {
        return $this->TokenCode;
    }

    public function getTransitiveTagKeys(): array
    {
        return $this->TransitiveTagKeys;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'AssumeRole', 'Version' => '2011-06-15'];
        if (null !== $this->RoleArn) {
            $payload['RoleArn'] = $this->RoleArn;
        }
        if (null !== $this->RoleSessionName) {
            $payload['RoleSessionName'] = $this->RoleSessionName;
        }
        if (null !== $this->PolicyArns) {
            $payload['PolicyArns'] = $this->PolicyArns;
        }
        if (null !== $this->Policy) {
            $payload['Policy'] = $this->Policy;
        }
        if (null !== $this->DurationSeconds) {
            $payload['DurationSeconds'] = $this->DurationSeconds;
        }
        if (null !== $this->Tags) {
            $payload['Tags'] = $this->Tags;
        }
        if (null !== $this->TransitiveTagKeys) {
            $payload['TransitiveTagKeys'] = $this->TransitiveTagKeys;
        }
        if (null !== $this->ExternalId) {
            $payload['ExternalId'] = $this->ExternalId;
        }
        if (null !== $this->SerialNumber) {
            $payload['SerialNumber'] = $this->SerialNumber;
        }
        if (null !== $this->TokenCode) {
            $payload['TokenCode'] = $this->TokenCode;
        }

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestUri(): string
    {
        return '/';
    }

    public function setDurationSeconds(?int $value): self
    {
        $this->DurationSeconds = $value;

        return $this;
    }

    public function setExternalId(?string $value): self
    {
        $this->ExternalId = $value;

        return $this;
    }

    public function setPolicy(?string $value): self
    {
        $this->Policy = $value;

        return $this;
    }

    public function setPolicyArns(array $value): self
    {
        $this->PolicyArns = $value;

        return $this;
    }

    public function setRoleArn(?string $value): self
    {
        $this->RoleArn = $value;

        return $this;
    }

    public function setRoleSessionName(?string $value): self
    {
        $this->RoleSessionName = $value;

        return $this;
    }

    public function setSerialNumber(?string $value): self
    {
        $this->SerialNumber = $value;

        return $this;
    }

    public function setTags(array $value): self
    {
        $this->Tags = $value;

        return $this;
    }

    public function setTokenCode(?string $value): self
    {
        $this->TokenCode = $value;

        return $this;
    }

    public function setTransitiveTagKeys(array $value): self
    {
        $this->TransitiveTagKeys = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['RoleArn', 'RoleSessionName'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        foreach ($this->PolicyArns as $item) {
            $item->validate();
        }
        foreach ($this->Tags as $item) {
            $item->validate();
        }
    }
}
