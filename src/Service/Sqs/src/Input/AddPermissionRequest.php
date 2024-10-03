<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AddPermissionRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue to which permissions are added.
     *
     * Queue URLs and names are case-sensitive.
     *
     * @required
     *
     * @var string|null
     */
    private $queueUrl;

    /**
     * The unique identification of the permission you're setting (for example, `AliceSendMessage`). Maximum 80 characters.
     * Allowed characters include alphanumeric characters, hyphens (`-`), and underscores (`_`).
     *
     * @required
     *
     * @var string|null
     */
    private $label;

    /**
     * The Amazon Web Services account numbers of the principals [^1] who are to receive permission. For information about
     * locating the Amazon Web Services account identification, see Your Amazon Web Services Identifiers [^2] in the *Amazon
     * SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/general/latest/gr/glos-chap.html#P
     * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-making-api-requests.html#sqs-api-request-authentication
     *
     * @required
     *
     * @var string[]|null
     */
    private $awsAccountIds;

    /**
     * The action the client wants to allow for the specified principal. Valid values: the name of any action or `*`.
     *
     * For more information about these actions, see Overview of Managing Access Permissions to Your Amazon Simple Queue
     * Service Resource [^1] in the *Amazon SQS Developer Guide*.
     *
     * Specifying `SendMessage`, `DeleteMessage`, or `ChangeMessageVisibility` for `ActionName.n` also grants permissions
     * for the corresponding batch versions of those actions: `SendMessageBatch`, `DeleteMessageBatch`, and
     * `ChangeMessageVisibilityBatch`.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-overview-of-managing-access.html
     *
     * @required
     *
     * @var string[]|null
     */
    private $actions;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   Label?: string,
     *   AWSAccountIds?: string[],
     *   Actions?: string[],
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->label = $input['Label'] ?? null;
        $this->awsAccountIds = $input['AWSAccountIds'] ?? null;
        $this->actions = $input['Actions'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueueUrl?: string,
     *   Label?: string,
     *   AWSAccountIds?: string[],
     *   Actions?: string[],
     *   '@region'?: string|null,
     * }|AddPermissionRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getActions(): array
    {
        return $this->actions ?? [];
    }

    /**
     * @return string[]
     */
    public function getAwsAccountIds(): array
    {
        return $this->awsAccountIds ?? [];
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getQueueUrl(): ?string
    {
        return $this->queueUrl;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.AddPermission',
            'Accept' => 'application/json',
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

    /**
     * @param string[] $value
     */
    public function setActions(array $value): self
    {
        $this->actions = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setAwsAccountIds(array $value): self
    {
        $this->awsAccountIds = $value;

        return $this;
    }

    public function setLabel(?string $value): self
    {
        $this->label = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->queueUrl = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queueUrl) {
            throw new InvalidArgument(\sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null === $v = $this->label) {
            throw new InvalidArgument(\sprintf('Missing parameter "Label" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Label'] = $v;
        if (null === $v = $this->awsAccountIds) {
            throw new InvalidArgument(\sprintf('Missing parameter "AWSAccountIds" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['AWSAccountIds'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['AWSAccountIds'][$index] = $listValue;
        }

        if (null === $v = $this->actions) {
            throw new InvalidArgument(\sprintf('Missing parameter "Actions" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['Actions'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['Actions'][$index] = $listValue;
        }

        return $payload;
    }
}
