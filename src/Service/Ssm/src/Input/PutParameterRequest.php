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
     * The fully qualified name of the parameter that you want to add to the system.
     *
     * > You can't enter the Amazon Resource Name (ARN) for a parameter, only the parameter name itself.
     *
     * The fully qualified name includes the complete hierarchy of the parameter path and name. For parameters in a
     * hierarchy, you must include a leading forward slash character (/) when you create or reference a parameter. For
     * example: `/Dev/DBServer/MySQL/db-string13`
     *
     * Naming Constraints:
     *
     * - Parameter names are case sensitive.
     * - A parameter name must be unique within an Amazon Web Services Region
     * - A parameter name can't be prefixed with "`aws`" or "`ssm`" (case-insensitive).
     * - Parameter names can include only the following symbols and letters: `a-zA-Z0-9_.-`
     *
     *   In addition, the slash character ( / ) is used to delineate hierarchies in parameter names. For example:
     *   `/Dev/Production/East/Project-ABC/MyParameter`
     * - A parameter name can't include spaces.
     * - Parameter hierarchies are limited to a maximum depth of fifteen levels.
     *
     * For additional information about valid values for parameter names, see Creating Systems Manager parameters [^1] in
     * the *Amazon Web Services Systems Manager User Guide*.
     *
     * > The maximum length constraint of 2048 characters listed below includes 1037 characters reserved for internal use by
     * > Systems Manager. The maximum length for a parameter name that you create is 1011 characters. This includes the
     * > characters in the ARN that precede the name you specify, such as `arn:aws:ssm:us-east-2:111122223333:parameter/`.
     *
     * [^1]: https://docs.aws.amazon.com/systems-manager/latest/userguide/sysman-paramstore-su-create.html
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * Information about the parameter that you want to add to the system. Optional but recommended.
     *
     * ! Don't enter personally identifiable information in this field.
     *
     * @var string|null
     */
    private $description;

    /**
     * The parameter value that you want to add to the system. Standard parameters have a value limit of 4 KB. Advanced
     * parameters have a value limit of 8 KB.
     *
     * > Parameters can't be referenced or nested in the values of other parameters. You can't include `{{}}` or
     * > `{{ssm:*parameter-name*}}` in a parameter value.
     *
     * @required
     *
     * @var string|null
     */
    private $value;

    /**
     * The type of parameter that you want to add to the system.
     *
     * > `SecureString` isn't currently supported for CloudFormation templates.
     *
     * Items in a `StringList` must be separated by a comma (,). You can't use other punctuation or special character to
     * escape items in the list. If you have a parameter value that requires a comma, then use the `String` data type.
     *
     * ! Specifying a parameter type isn't required when updating a parameter. You must specify a parameter type when
     * ! creating a parameter.
     *
     * @var ParameterType::*|null
     */
    private $type;

    /**
     * The Key Management Service (KMS) ID that you want to use to encrypt a parameter. Use a custom key for better
     * security. Required for parameters that use the `SecureString` data type.
     *
     * If you don't specify a key ID, the system uses the default key associated with your Amazon Web Services account which
     * is not as secure as using a custom key.
     *
     * - To use a custom KMS key, choose the `SecureString` data type with the `Key ID` parameter.
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
     * In this case, you could specify the following key-value pairs:
     *
     * - `Key=Resource,Value=S3bucket`
     * - `Key=OS,Value=Windows`
     * - `Key=ParameterType,Value=LicenseKey`
     *
     * > To add tags to an existing Systems Manager parameter, use the AddTagsToResource operation.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * The parameter tier to assign to a parameter.
     *
     * Parameter Store offers a standard tier and an advanced tier for parameters. Standard parameters have a content size
     * limit of 4 KB and can't be configured to use parameter policies. You can create a maximum of 10,000 standard
     * parameters for each Region in an Amazon Web Services account. Standard parameters are offered at no additional cost.
     *
     * Advanced parameters have a content size limit of 8 KB and can be configured to use parameter policies. You can create
     * a maximum of 100,000 advanced parameters for each Region in an Amazon Web Services account. Advanced parameters incur
     * a charge. For more information, see Managing parameter tiers [^1] in the *Amazon Web Services Systems Manager User
     * Guide*.
     *
     * You can change a standard parameter to an advanced parameter any time. But you can't revert an advanced parameter to
     * a standard parameter. Reverting an advanced parameter to a standard parameter would result in data loss because the
     * system would truncate the size of the parameter from 8 KB to 4 KB. Reverting would also remove any policies attached
     * to the parameter. Lastly, advanced parameters use a different form of encryption than standard parameters.
     *
     * If you no longer need an advanced parameter, or if you no longer want to incur charges for an advanced parameter, you
     * must delete it and recreate it as a new standard parameter.
     *
     * **Using the Default Tier Configuration**
     *
     * In `PutParameter` requests, you can specify the tier to create the parameter in. Whenever you specify a tier in the
     * request, Parameter Store creates or updates the parameter according to that request. However, if you don't specify a
     * tier in a request, Parameter Store assigns the tier based on the current Parameter Store default tier configuration.
     *
     * The default tier when you begin using Parameter Store is the standard-parameter tier. If you use the
     * advanced-parameter tier, you can specify one of the following as the default:
     *
     * - **Advanced**: With this option, Parameter Store evaluates all requests as advanced parameters.
     * - **Intelligent-Tiering**: With this option, Parameter Store evaluates each request to determine if the parameter is
     *   standard or advanced.
     *
     *   If the request doesn't include any options that require an advanced parameter, the parameter is created in the
     *   standard-parameter tier. If one or more options requiring an advanced parameter are included in the request,
     *   Parameter Store create a parameter in the advanced-parameter tier.
     *
     *   This approach helps control your parameter-related costs by always creating standard parameters unless an advanced
     *   parameter is necessary.
     *
     * Options that require an advanced parameter include the following:
     *
     * - The content size of the parameter is more than 4 KB.
     * - The parameter uses a parameter policy.
     * - More than 10,000 parameters already exist in your Amazon Web Services account in the current Amazon Web Services
     *   Region.
     *
     * For more information about configuring the default tier option, see Specifying a default parameter tier [^2] in the
     * *Amazon Web Services Systems Manager User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/systems-manager/latest/userguide/parameter-store-advanced-parameters.html
     * [^2]: https://docs.aws.amazon.com/systems-manager/latest/userguide/parameter-store-advanced-parameters.html#ps-default-tier
     *
     * @var ParameterTier::*|null
     */
    private $tier;

    /**
     * One or more policies to apply to a parameter. This operation takes a JSON array. Parameter Store, a capability of
     * Amazon Web Services Systems Manager supports the following policy types:
     *
     * Expiration: This policy deletes the parameter after it expires. When you create the policy, you specify the
     * expiration date. You can update the expiration date and time by updating the policy. Updating the *parameter* doesn't
     * affect the expiration date and time. When the expiration time is reached, Parameter Store deletes the parameter.
     *
     * ExpirationNotification: This policy initiates an event in Amazon CloudWatch Events that notifies you about the
     * expiration. By using this policy, you can receive notification before or after the expiration time is reached, in
     * units of days or hours.
     *
     * NoChangeNotification: This policy initiates a CloudWatch Events event if a parameter hasn't been modified for a
     * specified period of time. This policy type is useful when, for example, a secret needs to be changed within a period
     * of time, but it hasn't been changed.
     *
     * All existing policies are preserved until you send new policies or an empty policy. For more information about
     * parameter policies, see Assigning parameter policies [^1].
     *
     * [^1]: https://docs.aws.amazon.com/systems-manager/latest/userguide/parameter-store-policies.html
     *
     * @var string|null
     */
    private $policies;

    /**
     * The data type for a `String` parameter. Supported data types include plain text and Amazon Machine Image (AMI) IDs.
     *
     * **The following data type values are supported.**
     *
     * - `text`
     * - `aws:ec2:image`
     * - `aws:ssm:integration`
     *
     * When you create a `String` parameter and specify `aws:ec2:image`, Amazon Web Services Systems Manager validates the
     * parameter value is in the required format, such as `ami-12345abcdeEXAMPLE`, and that the specified AMI is available
     * in your Amazon Web Services account.
     *
     * > If the action is successful, the service sends back an HTTP 200 response which indicates a successful
     * > `PutParameter` call for all cases except for data type `aws:ec2:image`. If you call `PutParameter` with
     * > `aws:ec2:image` data type, a successful HTTP 200 response does not guarantee that your parameter was successfully
     * > created or updated. The `aws:ec2:image` value is validated asynchronously, and the `PutParameter` call returns
     * > before the validation is complete. If you submit an invalid AMI value, the PutParameter operation will return
     * > success, but the asynchronous validation will fail and the parameter will not be created or updated. To monitor
     * > whether your `aws:ec2:image` parameters are created successfully, see Setting up notifications or trigger actions
     * > based on Parameter Store events [^1]. For more information about AMI format validation , see Native parameter
     * > support for Amazon Machine Image IDs [^2].
     *
     * [^1]: https://docs.aws.amazon.com/systems-manager/latest/userguide/sysman-paramstore-cwe.html
     * [^2]: https://docs.aws.amazon.com/systems-manager/latest/userguide/parameter-store-ec2-aliases.html
     *
     * @var string|null
     */
    private $dataType;

    /**
     * @param array{
     *   Name?: string,
     *   Description?: null|string,
     *   Value?: string,
     *   Type?: null|ParameterType::*,
     *   KeyId?: null|string,
     *   Overwrite?: null|bool,
     *   AllowedPattern?: null|string,
     *   Tags?: null|array<Tag|array>,
     *   Tier?: null|ParameterTier::*,
     *   Policies?: null|string,
     *   DataType?: null|string,
     *   '@region'?: string|null,
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

    /**
     * @param array{
     *   Name?: string,
     *   Description?: null|string,
     *   Value?: string,
     *   Type?: null|ParameterType::*,
     *   KeyId?: null|string,
     *   Overwrite?: null|bool,
     *   AllowedPattern?: null|string,
     *   Tags?: null|array<Tag|array>,
     *   Tier?: null|ParameterTier::*,
     *   Policies?: null|string,
     *   DataType?: null|string,
     *   '@region'?: string|null,
     * }|PutParameterRequest $input
     */
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
