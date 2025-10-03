<?php

namespace AsyncAws\Translate\ValueObject;

/**
 * The custom terminology applied to the input text by Amazon Translate for the translated text response. This is
 * optional in the response and will only be present if you specified terminology input in the request. Currently, only
 * one terminology can be applied per TranslateText request.
 */
final class AppliedTerminology
{
    /**
     * The name of the custom terminology applied to the input text by Amazon Translate for the translated text response.
     *
     * @var string|null
     */
    private $name;

    /**
     * The specific terms of the custom terminology applied to the input text by Amazon Translate for the translated text
     * response. A maximum of 250 terms will be returned, and the specific terms applied will be the first 250 terms in the
     * source text.
     *
     * @var Term[]|null
     */
    private $terms;

    /**
     * @param array{
     *   Name?: string|null,
     *   Terms?: array<Term|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->terms = isset($input['Terms']) ? array_map([Term::class, 'create'], $input['Terms']) : null;
    }

    /**
     * @param array{
     *   Name?: string|null,
     *   Terms?: array<Term|array>|null,
     * }|AppliedTerminology $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Term[]
     */
    public function getTerms(): array
    {
        return $this->terms ?? [];
    }
}
