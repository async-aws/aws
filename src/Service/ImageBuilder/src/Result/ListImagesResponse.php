<?php

namespace AsyncAws\ImageBuilder\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\ImageBuilder\Enum\BuildType;
use AsyncAws\ImageBuilder\Enum\ImageSource;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;
use AsyncAws\ImageBuilder\ImageBuilderClient;
use AsyncAws\ImageBuilder\Input\ListImagesRequest;
use AsyncAws\ImageBuilder\ValueObject\ImageVersion;

/**
 * @implements \IteratorAggregate<ImageVersion>
 */
class ListImagesResponse extends Result implements \IteratorAggregate
{
    /**
     * The request ID that uniquely identifies this request.
     *
     * @var string|null
     */
    private $requestId;

    /**
     * The list of image semantic versions.
     *
     * > The semantic version has four nodes: <major>.<minor>.<patch>/<build>. You can assign values
     * > for the first three, and can filter on all of them.
     * >
     * > **Filtering:** With semantic versioning, you have the flexibility to use wildcards (x) to specify the most recent
     * > versions or nodes when selecting the base image or components for your recipe. When you use a wildcard in any node,
     * > all nodes to the right of the first wildcard must also be wildcards.
     *
     * @var ImageVersion[]
     */
    private $imageVersionList;

    /**
     * The next token used for paginated responses. When this field isn't empty, there are additional elements that the
     * service hasn't included in this request. Use this token with the next request to retrieve additional objects.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ImageVersion>
     */
    public function getImageVersionList(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->imageVersionList;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof ImageBuilderClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListImagesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listImages($input));
            } else {
                $nextPage = null;
            }

            yield from $page->imageVersionList;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over imageVersionList.
     *
     * @return \Traversable<ImageVersion>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getImageVersionList();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    public function getRequestId(): ?string
    {
        $this->initialize();

        return $this->requestId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->requestId = isset($data['requestId']) ? (string) $data['requestId'] : null;
        $this->imageVersionList = empty($data['imageVersionList']) ? [] : $this->populateResultImageVersionList($data['imageVersionList']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultImageVersion(array $json): ImageVersion
    {
        return new ImageVersion([
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'type' => isset($json['type']) ? (!ImageType::exists((string) $json['type']) ? ImageType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
            'version' => isset($json['version']) ? (string) $json['version'] : null,
            'platform' => isset($json['platform']) ? (!Platform::exists((string) $json['platform']) ? Platform::UNKNOWN_TO_SDK : (string) $json['platform']) : null,
            'osVersion' => isset($json['osVersion']) ? (string) $json['osVersion'] : null,
            'owner' => isset($json['owner']) ? (string) $json['owner'] : null,
            'dateCreated' => isset($json['dateCreated']) ? (string) $json['dateCreated'] : null,
            'buildType' => isset($json['buildType']) ? (!BuildType::exists((string) $json['buildType']) ? BuildType::UNKNOWN_TO_SDK : (string) $json['buildType']) : null,
            'imageSource' => isset($json['imageSource']) ? (!ImageSource::exists((string) $json['imageSource']) ? ImageSource::UNKNOWN_TO_SDK : (string) $json['imageSource']) : null,
        ]);
    }

    /**
     * @return ImageVersion[]
     */
    private function populateResultImageVersionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultImageVersion($item);
        }

        return $items;
    }
}
