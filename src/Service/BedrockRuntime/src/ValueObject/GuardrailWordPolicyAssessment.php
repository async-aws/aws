<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The word policy assessment.
 */
final class GuardrailWordPolicyAssessment
{
    /**
     * Custom words in the assessment.
     *
     * @var GuardrailCustomWord[]
     */
    private $customWords;

    /**
     * Managed word lists in the assessment.
     *
     * @var GuardrailManagedWord[]
     */
    private $managedWordLists;

    /**
     * @param array{
     *   customWords: array<GuardrailCustomWord|array>,
     *   managedWordLists: array<GuardrailManagedWord|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->customWords = isset($input['customWords']) ? array_map([GuardrailCustomWord::class, 'create'], $input['customWords']) : $this->throwException(new InvalidArgument('Missing required field "customWords".'));
        $this->managedWordLists = isset($input['managedWordLists']) ? array_map([GuardrailManagedWord::class, 'create'], $input['managedWordLists']) : $this->throwException(new InvalidArgument('Missing required field "managedWordLists".'));
    }

    /**
     * @param array{
     *   customWords: array<GuardrailCustomWord|array>,
     *   managedWordLists: array<GuardrailManagedWord|array>,
     * }|GuardrailWordPolicyAssessment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailCustomWord[]
     */
    public function getCustomWords(): array
    {
        return $this->customWords;
    }

    /**
     * @return GuardrailManagedWord[]
     */
    public function getManagedWordLists(): array
    {
        return $this->managedWordLists;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
