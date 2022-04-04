<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\ValueObject\Tag;

/**
 * Represents the output for `ListTagsForStream`.
 */
class ListTagsForStreamOutput extends Result
{
    /**
     * A list of tags associated with `StreamName`, starting with the first tag after `ExclusiveStartTagKey` and up to the
     * specified `Limit`.
     */
    private $tags;

    /**
     * If set to `true`, more tags are available. To request additional tags, set `ExclusiveStartTagKey` to the key of the
     * last tag returned.
     */
    private $hasMoreTags;

    public function getHasMoreTags(): bool
    {
        $this->initialize();

        return $this->hasMoreTags;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        $this->initialize();

        return $this->tags;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->tags = $this->populateResultTagList($data['Tags']);
        $this->hasMoreTags = filter_var($data['HasMoreTags'], \FILTER_VALIDATE_BOOLEAN);
    }

    private function populateResultTag(array $json): Tag
    {
        return new Tag([
            'Key' => (string) $json['Key'],
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }
}
