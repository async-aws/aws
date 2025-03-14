<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * A prompt router trace.
 */
final class PromptRouterTrace
{
    /**
     * The ID of the invoked model.
     *
     * @var string|null
     */
    private $invokedModelId;

    /**
     * @param array{
     *   invokedModelId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->invokedModelId = $input['invokedModelId'] ?? null;
    }

    /**
     * @param array{
     *   invokedModelId?: null|string,
     * }|PromptRouterTrace $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInvokedModelId(): ?string
    {
        return $this->invokedModelId;
    }
}
