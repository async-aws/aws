<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The output from a call to Converse [^1].
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 */
final class ConverseOutput
{
    /**
     * The message that the model generates.
     *
     * @var Message|null
     */
    private $message;

    /**
     * @param array{
     *   message?: null|Message|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->message = isset($input['message']) ? Message::create($input['message']) : null;
    }

    /**
     * @param array{
     *   message?: null|Message|array,
     * }|ConverseOutput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }
}
