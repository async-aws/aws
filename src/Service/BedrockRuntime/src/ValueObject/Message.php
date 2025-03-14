<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\ConversationRole;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A message input, or returned from, a call to Converse [^1] or ConverseStream [^2].
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 * [^2]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_ConverseStream.html
 */
final class Message
{
    /**
     * The role that the message plays in the message.
     *
     * @var ConversationRole::*
     */
    private $role;

    /**
     * The message content. Note the following restrictions:
     *
     * - You can include up to 20 images. Each image's size, height, and width must be no more than 3.75 MB, 8000 px, and
     *   8000 px, respectively.
     * - You can include up to five documents. Each document's size must be no more than 4.5 MB.
     * - If you include a `ContentBlock` with a `document` field in the array, you must also include a `ContentBlock` with a
     *   `text` field.
     * - You can only include images and documents if the `role` is `user`.
     *
     * @var ContentBlock[]
     */
    private $content;

    /**
     * @param array{
     *   role: ConversationRole::*,
     *   content: array<ContentBlock|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->role = $input['role'] ?? $this->throwException(new InvalidArgument('Missing required field "role".'));
        $this->content = isset($input['content']) ? array_map([ContentBlock::class, 'create'], $input['content']) : $this->throwException(new InvalidArgument('Missing required field "content".'));
    }

    /**
     * @param array{
     *   role: ConversationRole::*,
     *   content: array<ContentBlock|array>,
     * }|Message $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ContentBlock[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return ConversationRole::*
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->role;
        if (!ConversationRole::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "role" for "%s". The value "%s" is not a valid "ConversationRole".', __CLASS__, $v));
        }
        $payload['role'] = $v;
        $v = $this->content;

        $index = -1;
        $payload['content'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['content'][$index] = $listValue->requestBody();
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
