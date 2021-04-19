<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetCelebrityInfoResponse extends Result
{
    /**
     * An array of URLs pointing to additional celebrity information.
     */
    private $urls = [];

    /**
     * The name of the celebrity.
     */
    private $name;

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
