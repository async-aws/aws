<?php

namespace AsyncAws\Ssm\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Ssm\Enum\ParameterTier;
use AsyncAws\Ssm\Enum\ParameterType;
use AsyncAws\Ssm\ValueObject\Tag;

final class PutParameterRequest extends Input
{
    /**
     * The fully qualified name of the parameter that you want to add to the system. The fully qualified name includes the
     * complete hierarchy of the parameter path and name. For parameters in a hierarchy, you must include a leading forward
     * slash character (/) when you create or reference a parameter. For example: `/Dev/DBServer/MySQL/db-string13`.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * Information about the parameter that you want to add to the system. Optional but recommended.
     *
     * @var string|null
     */
    private $description;

    /**
     * The parameter value that you want to add to the system. Standard parameters have a value limit of 4 KB. Advanced
     * parameters have a value limit of 8 KB.
     *
     * @required
     *
     * @var string|null
     */
    private $value;

    /**
     * The type of parameter that you want to add to the system.
     *
     * @var ParameterType::*|null
     */
    private $type;

    /**
     * The Key Management Service (KMS) ID that you want to use to encrypt a parameter. Use a custom key for better
     * security. Required for parameters that use the `SecureString` data type.
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Overwrite an existing parameter. The default value is `false`.
     *
     * @var bool|null
     */
    private $overwrite;

    /**
     * A regular expression used to validate the parameter value. For example, for String types with values restricted to
     * numbers, you can specify the following: AllowedPattern=^\d+$.
     *
     * @var string|null
     */
    private $allowedPattern;

    /**
     * Optional metadata that you assign to a resource. Tags enable you to categorize a resource in different ways, such as
     * by purpose, owner, or environment. For example, you might want to tag a Systems Manager parameter to identify the
     * type of resource to which it applies, the environment, or the type of configuration data referenced by the parameter.
     * In this case, you could specify the following key-value pairs:.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * The parameter tier to assign to a parameter.
     *
     * @var ParameterTier::*|null
     */
    private $tier;

    /**
     * One or more policies to apply to a parameter. This operation takes a JSON array. Parameter Store, a capability of
     * Amazon Web Services Systems Manager supports the following policy types:.
     *
     * @var string|null
     */
    private $policies;

    /**
     * The data type for a `String` parameter. Supported data types include plain text and Amazon Machine Image (AMI) IDs.
     *
     * @var string|null
     */
    private $dataType;

    /**
     * @param array{
     *   Name?: string,
     *   Description?: string,
     *   Value?: string,
     *   Type?: ParameterType::*,
     *   KeyId?: string,
     *   Overwrite?: bool,
     *   AllowedPattern?: string,
     *   Tags?: Tag[],
     *   Tier?: ParameterTier::*,
     *   Policies?: string,
     *   DataType?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->name = $input['Name'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->value = $input['Value'] ?? null;
        $this->type = $input['Type'] ?? null;
        $this->keyId = $input['KeyId'] ?? null;
        $this->overwrite = $input['Overwrite'] ?? null;
        $this->allowedPattern = $input['AllowedPattern'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->tier = $input['Tier'] ?? null;
        $this->policies = $input['Policies'] ?? null;
        $this->dataType = $input['DataType'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAllowedPattern(): ?string
    {
        return $this->allowedPattern;
    }

    public function getDataType(): ?string
    {
        return $this->dataType;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOverwrite(): ?bool
    {
        return $this->overwrite;
    }

    public function getPolicies(): ?string
    {
        return $this->policies;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @return ParameterTier::*|null
     */
    public function getTier(): ?string
    {
        return $this->tier;
    }

    /**
     * @return ParameterType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonSSM.PutParameter',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAllowedPattern(?string $value): self
    {
        $this->allowedPattern = $value;

        return $this;
    }

    public function setDataType(?string $value): self
    {
        $this->dataType = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setKeyId(?string $value): self
    {
        $this->keyId = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setOverwrite(?bool $value): self
    {
        $this->overwrite = $value;

        return $this;
    }

    public function setPolicies(?string $value): self
    {
        $this->policies = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    /**
     * @param ParameterTier::*|null $value
     */
    public function setTier(?string $value): self
    {
        $this->tier = $value;

        return $this;
    }

    /**
     * @param ParameterType::*|null $value
     */
    public function setType(?string $value): self
    {
        $this->type = $value;

        return $this;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null === $v = $this->value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Value'] = $v;
        if (null !== $v = $this->type) {
            if (!ParameterType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "ParameterType".', __CLASS__, $v));
            }
            $payload['Type'] = $v;
        }
        if (null !== $v = $this->keyId) {
            $payload['KeyId'] = $v;
        }
        if (null !== $v = $this->overwrite) {
            $payload['Overwrite'] = (bool) $v;
        }
        if (null !== $v = $this->allowedPattern) {
            $payload['AllowedPattern'] = $v;
        }
        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Tags'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->tier) {
            if (!ParameterTier::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Tier" for "%s". The value "%s" is not a valid "ParameterTier".', __CLASS__, $v));
            }
            $payload['Tier'] = $v;
        }
        if (null !== $v = $this->policies) {
            $payload['Policies'] = $v;
        }
        if (null !== $v = $this->dataType) {
            $payload['DataType'] = $v;
        }

        return $payload;
    }
}
