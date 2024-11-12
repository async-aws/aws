<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\ValueObject\Tag;

class GetObjectTaggingOutput extends Result
{
    /**
     * The versionId of the object for which you got the tagging information.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * Contains the tag set.
     *
     * @var Tag[]
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

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->versionId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->versionId = $headers['x-amz-version-id'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->tagSet = $this->populateResultTagSet($data->TagSet);
    }

    private function populateResultTag(\SimpleXMLElement $xml): Tag
    {
        return new Tag([
            'Key' => (string) $xml->Key,
            'Value' => (string) $xml->Value,
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagSet(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Tag as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }
}
