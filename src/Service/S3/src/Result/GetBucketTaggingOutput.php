<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\ValueObject\Tag;

class GetBucketTaggingOutput extends Result
{
    /**
     * Contains the tag set.
     */
    private $tagSet;

    /**
     * @return Tag[]
     */
    public function getTagSet(): array
    {
        $this->initialize();

        return $this->tagSet;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->tagSet = $this->populateResultTagSet($data->TagSet);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagSet(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Tag as $item) {
            $items[] = new Tag([
                'Key' => (string) $item->Key,
                'Value' => (string) $item->Value,
            ]);
        }

        return $items;
    }
}
