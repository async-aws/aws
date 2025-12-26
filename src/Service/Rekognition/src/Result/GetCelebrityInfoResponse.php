<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\KnownGenderType;
use AsyncAws\Rekognition\ValueObject\KnownGender;

class GetCelebrityInfoResponse extends Result
{
    /**
     * An array of URLs pointing to additional celebrity information.
     *
     * @var string[]
     */
    private $urls;

    /**
     * The name of the celebrity.
     *
     * @var string|null
     */
    private $name;

    /**
     * Retrieves the known gender for the celebrity.
     *
     * @var KnownGender|null
     */
    private $knownGender;

    public function getKnownGender(): ?KnownGender
    {
        $this->initialize();

        return $this->knownGender;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getUrls(): array
    {
        $this->initialize();

        return $this->urls;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->urls = empty($data['Urls']) ? [] : $this->populateResultUrls($data['Urls']);
        $this->name = isset($data['Name']) ? (string) $data['Name'] : null;
        $this->knownGender = empty($data['KnownGender']) ? null : $this->populateResultKnownGender($data['KnownGender']);
    }

    private function populateResultKnownGender(array $json): KnownGender
    {
        return new KnownGender([
            'Type' => isset($json['Type']) ? (!KnownGenderType::exists((string) $json['Type']) ? KnownGenderType::UNKNOWN_TO_SDK : (string) $json['Type']) : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultUrls(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
