<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sns\ValueObject\Tag;

/**
 * Input for CreateTopic action.
 */
final class CreateTopicInput extends Input
{
    /**
     * The name of the topic you want to create.
     *
     * Constraints: Topic names must be made up of only uppercase and lowercase ASCII letters, numbers, underscores, and
     * hyphens, and must be between 1 and 256 characters long.
     *
     * For a FIFO (first-in-first-out) topic, the name must end with the `.fifo` suffix.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * A map of attributes with their corresponding values.
     *
     * The following lists names, descriptions, and values of the special request parameters that the `CreateTopic` action
     * uses:
     *
     * - `DeliveryPolicy` – The policy that defines how Amazon SNS retries failed deliveries to HTTP/S endpoints.
     * - `DisplayName` – The display name to use for a topic with SMS subscriptions.
     * - `FifoTopic` – Set to true to create a FIFO topic.
     * - `Policy` – The policy that defines who can access your topic. By default, only the topic owner can publish or
     *   subscribe to the topic.
     * - `SignatureVersion` – The signature version corresponds to the hashing algorithm used while creating the signature
     *   of the notifications, subscription confirmations, or unsubscribe confirmation messages sent by Amazon SNS. By
     *   default, `SignatureVersion` is set to `1`.
     * - `TracingConfig` – Tracing mode of an Amazon SNS topic. By default `TracingConfig` is set to `PassThrough`, and
     *   the topic passes through the tracing header it receives from an Amazon SNS publisher to its subscriptions. If set
     *   to `Active`, Amazon SNS will vend X-Ray segment data to topic owner account if the sampled flag in the tracing
     *   header is true. This is only supported on standard topics.
     *
     * The following attribute applies only to server-side encryption [^1]:
     *
     * - `KmsMasterKeyId` – The ID of an Amazon Web Services managed customer master key (CMK) for Amazon SNS or a custom
     *   CMK. For more information, see Key Terms [^2]. For more examples, see KeyId [^3] in the *Key Management Service API
     *   Reference*.
     *
     * The following attributes apply only to FIFO topics [^4]:
     *
     * - `ArchivePolicy` – The policy that sets the retention period for messages stored in the message archive of an
     *   Amazon SNS FIFO topic.
     * - `ContentBasedDeduplication` – Enables content-based deduplication for FIFO topics.
     *
     *   - By default, `ContentBasedDeduplication` is set to `false`. If you create a FIFO topic and this attribute is
     *     `false`, you must specify a value for the `MessageDeduplicationId` parameter for the Publish [^5] action.
     *   - When you set `ContentBasedDeduplication` to `true`, Amazon SNS uses a SHA-256 hash to generate the
     *     `MessageDeduplicationId` using the body of the message (but not the attributes of the message).
     *
     *     (Optional) To override the generated value, you can specify a value for the `MessageDeduplicationId` parameter
     *     for the `Publish` action.
     *
     *
     * - `FifoThroughputScope` – Enables higher throughput for your FIFO topic by adjusting the scope of deduplication.
     *   This attribute has two possible values:
     *
     *   - `Topic` – The scope of message deduplication is across the entire topic. This is the default value and
     *     maintains existing behavior, with a maximum throughput of 3000 messages per second or 20MB per second, whichever
     *     comes first.
     *   - `MessageGroup` – The scope of deduplication is within each individual message group, which enables higher
     *     throughput per topic subject to regional quotas. For more information on quotas or to request an increase, see
     *     Amazon SNS service quotas [^6] in the Amazon Web Services General Reference.
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/sns-server-side-encryption.html
     * [^2]: https://docs.aws.amazon.com/sns/latest/dg/sns-server-side-encryption.html#sse-key-terms
     * [^3]: https://docs.aws.amazon.com/kms/latest/APIReference/API_DescribeKey.html#API_DescribeKey_RequestParameters
     * [^4]: https://docs.aws.amazon.com/sns/latest/dg/sns-fifo-topics.html
     * [^5]: https://docs.aws.amazon.com/sns/latest/api/API_Publish.html
     * [^6]: https://docs.aws.amazon.com/general/latest/gr/sns.html
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * The list of tags to add to a new topic.
     *
     * > To be able to tag a topic on creation, you must have the `sns:CreateTopic` and `sns:TagResource` permissions.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * The body of the policy document you want to use for this topic.
     *
     * You can only add one policy per topic.
     *
     * The policy must be in JSON string format.
     *
     * Length Constraints: Maximum length of 30,720.
     *
     * @var string|null
     */
    private $dataProtectionPolicy;

    /**
     * @param array{
     *   Name?: string,
     *   Attributes?: null|array<string, string>,
     *   Tags?: null|array<Tag|array>,
     *   DataProtectionPolicy?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->name = $input['Name'] ?? null;
        $this->attributes = $input['Attributes'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->dataProtectionPolicy = $input['DataProtectionPolicy'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Name?: string,
     *   Attributes?: null|array<string, string>,
     *   Tags?: null|array<Tag|array>,
     *   DataProtectionPolicy?: null|string,
     *   '@region'?: string|null,
     * }|CreateTopicInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getDataProtectionPolicy(): ?string
    {
        return $this->dataProtectionPolicy;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
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
        $body = http_build_query(['Action' => 'CreateTopic', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, string> $value
     */
    public function setAttributes(array $value): self
    {
        $this->attributes = $value;

        return $this;
    }

    public function setDataProtectionPolicy(?string $value): self
    {
        $this->dataProtectionPolicy = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

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

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(\sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->attributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["Attributes.entry.$index.key"] = $mapKey;
                $payload["Attributes.entry.$index.value"] = $mapValue;
            }
        }
        if (null !== $v = $this->tags) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["Tags.member.$index.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->dataProtectionPolicy) {
            $payload['DataProtectionPolicy'] = $v;
        }

        return $payload;
    }
}
