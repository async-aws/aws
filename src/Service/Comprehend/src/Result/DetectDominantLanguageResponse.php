<?php

namespace AsyncAws\Comprehend\Result;

use AsyncAws\Comprehend\ValueObject\DominantLanguage;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DetectDominantLanguageResponse extends Result
{
    /**
     * Array of languages that Amazon Comprehend detected in the input text. The array is sorted in descending order of the
     * score (the dominant language is always the first element in the array).
     */
    private $languages;

    /**
     * @return DominantLanguage[]
     */
    public function getLanguages(): array
    {
        $this->initialize();

        return $this->languages;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->languages = empty($data['Languages']) ? [] : $this->populateResultListOfDominantLanguages($data['Languages']);
    }

    private function populateResultDominantLanguage(array $json): DominantLanguage
    {
        return new DominantLanguage([
            'LanguageCode' => isset($json['LanguageCode']) ? (string) $json['LanguageCode'] : null,
            'Score' => isset($json['Score']) ? (float) $json['Score'] : null,
        ]);
    }

    /**
     * @return DominantLanguage[]
     */
    private function populateResultListOfDominantLanguages(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultDominantLanguage($item);
        }

        return $items;
    }
}
