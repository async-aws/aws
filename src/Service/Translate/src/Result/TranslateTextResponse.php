<?php

namespace AsyncAws\Translate\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Translate\Enum\Brevity;
use AsyncAws\Translate\Enum\Formality;
use AsyncAws\Translate\Enum\Profanity;
use AsyncAws\Translate\ValueObject\AppliedTerminology;
use AsyncAws\Translate\ValueObject\Term;
use AsyncAws\Translate\ValueObject\TranslationSettings;

class TranslateTextResponse extends Result
{
    /**
     * The translated text.
     *
     * @var string
     */
    private $translatedText;

    /**
     * The language code for the language of the source text.
     *
     * @var string
     */
    private $sourceLanguageCode;

    /**
     * The language code for the language of the target text.
     *
     * @var string
     */
    private $targetLanguageCode;

    /**
     * The names of the custom terminologies applied to the input text by Amazon Translate for the translated text response.
     *
     * @var AppliedTerminology[]
     */
    private $appliedTerminologies;

    /**
     * Optional settings that modify the translation output.
     *
     * @var TranslationSettings|null
     */
    private $appliedSettings;

    public function getAppliedSettings(): ?TranslationSettings
    {
        $this->initialize();

        return $this->appliedSettings;
    }

    /**
     * @return AppliedTerminology[]
     */
    public function getAppliedTerminologies(): array
    {
        $this->initialize();

        return $this->appliedTerminologies;
    }

    public function getSourceLanguageCode(): string
    {
        $this->initialize();

        return $this->sourceLanguageCode;
    }

    public function getTargetLanguageCode(): string
    {
        $this->initialize();

        return $this->targetLanguageCode;
    }

    public function getTranslatedText(): string
    {
        $this->initialize();

        return $this->translatedText;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->translatedText = (string) $data['TranslatedText'];
        $this->sourceLanguageCode = (string) $data['SourceLanguageCode'];
        $this->targetLanguageCode = (string) $data['TargetLanguageCode'];
        $this->appliedTerminologies = empty($data['AppliedTerminologies']) ? [] : $this->populateResultAppliedTerminologyList($data['AppliedTerminologies']);
        $this->appliedSettings = empty($data['AppliedSettings']) ? null : $this->populateResultTranslationSettings($data['AppliedSettings']);
    }

    private function populateResultAppliedTerminology(array $json): AppliedTerminology
    {
        return new AppliedTerminology([
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Terms' => !isset($json['Terms']) ? null : $this->populateResultTermList($json['Terms']),
        ]);
    }

    /**
     * @return AppliedTerminology[]
     */
    private function populateResultAppliedTerminologyList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAppliedTerminology($item);
        }

        return $items;
    }

    private function populateResultTerm(array $json): Term
    {
        return new Term([
            'SourceText' => isset($json['SourceText']) ? (string) $json['SourceText'] : null,
            'TargetText' => isset($json['TargetText']) ? (string) $json['TargetText'] : null,
        ]);
    }

    /**
     * @return Term[]
     */
    private function populateResultTermList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTerm($item);
        }

        return $items;
    }

    private function populateResultTranslationSettings(array $json): TranslationSettings
    {
        return new TranslationSettings([
            'Formality' => isset($json['Formality']) ? (!Formality::exists((string) $json['Formality']) ? Formality::UNKNOWN_TO_SDK : (string) $json['Formality']) : null,
            'Profanity' => isset($json['Profanity']) ? (!Profanity::exists((string) $json['Profanity']) ? Profanity::UNKNOWN_TO_SDK : (string) $json['Profanity']) : null,
            'Brevity' => isset($json['Brevity']) ? (!Brevity::exists((string) $json['Brevity']) ? Brevity::UNKNOWN_TO_SDK : (string) $json['Brevity']) : null,
        ]);
    }
}
