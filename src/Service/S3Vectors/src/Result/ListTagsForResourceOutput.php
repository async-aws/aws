<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class ListTagsForResourceOutput extends Result
{
    /**
     * The user-defined tags that are applied to the S3 Vectors resource. For more information, see Tagging for cost
     * allocation or attribute-based access control (ABAC) [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/tagging.html
     *
     * @var array<string, string>
     */
    private $tags;

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        $this->initialize();

        return $this->tags;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->tags = $this->populateResultTagsMap($data['tags']);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultTagsMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }
}
