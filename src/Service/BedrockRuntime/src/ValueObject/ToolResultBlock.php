<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\ToolResultStatus;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A tool result block that contains the results for a tool request that the model previously made.
 */
final class ToolResultBlock
{
    /**
     * The ID of the tool request that this is the result for.
     *
     * @var string
     */
    private $toolUseId;

    /**
     * The content for tool result content block.
     *
     * @var ToolResultContentBlock[]
     */
    private $content;

    /**
     * The status for the tool result content block.
     *
     * > This field is only supported Anthropic Claude 3 models.
     *
     * @var ToolResultStatus::*|null
     */
    private $status;

    /**
     * @param array{
     *   toolUseId: string,
     *   content: array<ToolResultContentBlock|array>,
     *   status?: null|ToolResultStatus::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->toolUseId = $input['toolUseId'] ?? $this->throwException(new InvalidArgument('Missing required field "toolUseId".'));
        $this->content = isset($input['content']) ? array_map([ToolResultContentBlock::class, 'create'], $input['content']) : $this->throwException(new InvalidArgument('Missing required field "content".'));
        $this->status = $input['status'] ?? null;
    }

    /**
     * @param array{
     *   toolUseId: string,
     *   content: array<ToolResultContentBlock|array>,
     *   status?: null|ToolResultStatus::*,
     * }|ToolResultBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ToolResultContentBlock[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return ToolResultStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getToolUseId(): string
    {
        return $this->toolUseId;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->toolUseId;
        $payload['toolUseId'] = $v;
        $v = $this->content;

        $index = -1;
        $payload['content'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['content'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->status) {
            if (!ToolResultStatus::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "status" for "%s". The value "%s" is not a valid "ToolResultStatus".', __CLASS__, $v));
            }
            $payload['status'] = $v;
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
