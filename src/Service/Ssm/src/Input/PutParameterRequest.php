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
    private $Name;

    /**
     * Information about the parameter that you want to add to the system. Optional but recommended.
     *
     * @var string|null
     */
    private $Description;

    /**
     * The parameter value that you want to add to the system. Standard parameters have a value limit of 4 KB. Advanced
     * parameters have a value limit of 8 KB.
     *
     * @required
     *
     * @var string|null
     */
    private $Value;

    /**
     * The type of parameter that you want to add to the system.
     *
     * @var null|ParameterType::*
     */
    private $Type;

    /**
     * The KMS Key ID that you want to use to encrypt a parameter. Either the default AWS Key Management Service (AWS KMS)
     * key automatically assigned to your AWS account or a custom key. Required for parameters that use the `SecureString`
     * data type.
     *
     * @var string|null
     */
    private $KeyId;

    /**
     * Overwrite an existing parameter. If not specified, will default to "false".
     *
     * @var bool|null
     */
    private $Overwrite;

    /**
     * A regular expression used to validate the parameter value. For example, for String types with values restricted to
     * numbers, you can specify the following: AllowedPattern=^\d+$.
     *
     * @var string|null
     */
    private $AllowedPattern;

    /**
     * Optional metadata that you assign to a resource. Tags enable you to categorize a resource in different ways, such as
     * by purpose, owner, or environment. For example, you might want to tag a Systems Manager parameter to identify the
     * type of resource to which it applies, the environment, or the type of configuration data referenced by the parameter.
     * In this case, you could specify the following key name/value pairs:.
     *
     * @var Tag[]|null
     */
    private $Tags;

    /**
     * The parameter tier to assign to a parameter.
     *
     * @var null|ParameterTier::*
     */
    private $Tier;

    /**
     * One or more policies to apply to a parameter. This action takes a JSON array. Parameter Store supports the following
     * policy types:.
     *
     * @var string|null
     */
    private $Policies;

    /**
     * The data type for a `String` parameter. Supported data types include plain text and Amazon Machine Image IDs.
     *
     * @var string|null
     */
    private $DataType;

    /**
     * @param array{
     *   Name?: string,
     *   Description?: string,
     *   Value?: string,
     *   Type?: \AsyncAws\Ssm\Enum\ParameterType::*,
     *   KeyId?: string,
     *   Overwrite?: bool,
     *   AllowedPattern?: string,
     *   Tags?: \AsyncAws\Ssm\ValueObject\Tag[],
     *   Tier?: \AsyncAws\Ssm\Enum\ParameterTier::*,
     *   Policies?: string,
     *   DataType?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Name = $input['Name'] ?? null;
        $this->Description = $input['Description'] ?? null;
        $this->Value = $input['Value'] ?? null;
        $this->Type = $input['Type'] ?? null;
        $this->KeyId = $input['KeyId'] ?? null;
        $this->Overwrite = $input['Overwrite'] ?? null;
        $this->AllowedPattern = $input['AllowedPattern'] ?? null;
        $this->Tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->Tier = $input['Tier'] ?? null;
        $this->Policies = $input['Policies'] ?? null;
        $this->DataType = $input['DataType'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAllowedPattern(): ?string
    {
        return $this->AllowedPattern;
    }

    public function getDataType(): ?string
    {
        return $this->DataType;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function getKeyId(): ?string
    {
        return $this->KeyId;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function getOverwrite(): ?bool
    {
        return $this->Overwrite;
    }

    public function getPolicies(): ?string
    {
        return $this->Policies;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags ?? [];
    }

    /**
     * @return ParameterTier::*|null
     */
    public function getTier(): ?string
    {
        return $this->Tier;
    }

    /**
     * @return ParameterType::*|null
     */
    public function getType(): ?string
    {
        return $this->Type;
    }

    public function getValue(): ?string
    {
        return $this->Value;
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAllowedPattern(?string $value): self
    {
        $this->AllowedPattern = $value;

        return $this;
    }

    public function setDataType(?string $value): self
    {
        $this->DataType = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->Description = $value;

        return $this;
    }

    public function setKeyId(?string $value): self
    {
        $this->KeyId = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->Name = $value;

        return $this;
    }

    public function setOverwrite(?bool $value): self
    {
        $this->Overwrite = $value;

        return $this;
    }

    public function setPolicies(?string $value): self
    {
        $this->Policies = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->Tags = $value;

        return $this;
    }

    /**
     * @param ParameterTier::*|null $value
     */
    public function setTier(?string $value): self
    {
        $this->Tier = $value;

        return $this;
    }

    /**
     * @param ParameterType::*|null $value
     */
    public function setType(?string $value): self
    {
        $this->Type = $value;

        return $this;
    }

    public function setValue(?string $value): self
    {
        $this->Value = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->Description) {
            $payload['Description'] = $v;
        }
        if (null === $v = $this->Value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Value'] = $v;
        if (null !== $v = $this->Type) {
            if (!ParameterType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "ParameterType".', __CLASS__, $v));
            }
            $payload['Type'] = $v;
        }
        if (null !== $v = $this->KeyId) {
            $payload['KeyId'] = $v;
        }
        if (null !== $v = $this->Overwrite) {
            $payload['Overwrite'] = (bool) $v;
        }
        if (null !== $v = $this->AllowedPattern) {
            $payload['AllowedPattern'] = $v;
        }
        if (null !== $v = $this->Tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Tags'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->Tier) {
            if (!ParameterTier::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Tier" for "%s". The value "%s" is not a valid "ParameterTier".', __CLASS__, $v));
            }
            $payload['Tier'] = $v;
        }
        if (null !== $v = $this->Policies) {
            $payload['Policies'] = $v;
        }
        if (null !== $v = $this->DataType) {
            $payload['DataType'] = $v;
        }

        return $payload;
    }
}
