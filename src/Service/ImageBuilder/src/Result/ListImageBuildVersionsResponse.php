<?php

namespace AsyncAws\ImageBuilder\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\ImageBuilder\Enum\BuildType;
use AsyncAws\ImageBuilder\Enum\ImageSource;
use AsyncAws\ImageBuilder\Enum\ImageStatus;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;
use AsyncAws\ImageBuilder\ImageBuilderClient;
use AsyncAws\ImageBuilder\Input\ListImageBuildVersionsRequest;
use AsyncAws\ImageBuilder\ValueObject\Ami;
use AsyncAws\ImageBuilder\ValueObject\Container;
use AsyncAws\ImageBuilder\ValueObject\ImageLoggingConfiguration;
use AsyncAws\ImageBuilder\ValueObject\ImageState;
use AsyncAws\ImageBuilder\ValueObject\ImageSummary;
use AsyncAws\ImageBuilder\ValueObject\OutputResources;

/**
 * @implements \IteratorAggregate<ImageSummary>
 */
class ListImageBuildVersionsResponse extends Result implements \IteratorAggregate
{
    /**
     * The request ID that uniquely identifies this request.
     *
     * @var string|null
     */
    private $requestId;

    /**
     * The list of image build versions.
     *
     * @var ImageSummary[]
     */
    private $imageSummaryList;

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
     * @return iterable<ImageSummary>
     */
    public function getImageSummaryList(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->imageSummaryList;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof ImageBuilderClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListImageBuildVersionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listImageBuildVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->imageSummaryList;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over imageSummaryList.
     *
     * @return \Traversable<ImageSummary>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getImageSummaryList();
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
        $this->imageSummaryList = empty($data['imageSummaryList']) ? [] : $this->populateResultImageSummaryList($data['imageSummaryList']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultAmi(array $json): Ami
    {
        return new Ami([
            'region' => isset($json['region']) ? (string) $json['region'] : null,
            'image' => isset($json['image']) ? (string) $json['image'] : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'state' => empty($json['state']) ? null : $this->populateResultImageState($json['state']),
            'accountId' => isset($json['accountId']) ? (string) $json['accountId'] : null,
        ]);
    }

    /**
     * @return Ami[]
     */
    private function populateResultAmiList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAmi($item);
        }

        return $items;
    }

    private function populateResultContainer(array $json): Container
    {
        return new Container([
            'region' => isset($json['region']) ? (string) $json['region'] : null,
            'imageUris' => !isset($json['imageUris']) ? null : $this->populateResultStringList($json['imageUris']),
        ]);
    }

    /**
     * @return Container[]
     */
    private function populateResultContainerList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultContainer($item);
        }

        return $items;
    }

    private function populateResultImageLoggingConfiguration(array $json): ImageLoggingConfiguration
    {
        return new ImageLoggingConfiguration([
            'logGroupName' => isset($json['logGroupName']) ? (string) $json['logGroupName'] : null,
        ]);
    }

    private function populateResultImageState(array $json): ImageState
    {
        return new ImageState([
            'status' => isset($json['status']) ? (!ImageStatus::exists((string) $json['status']) ? ImageStatus::UNKNOWN_TO_SDK : (string) $json['status']) : null,
            'reason' => isset($json['reason']) ? (string) $json['reason'] : null,
        ]);
    }

    private function populateResultImageSummary(array $json): ImageSummary
    {
        return new ImageSummary([
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'type' => isset($json['type']) ? (!ImageType::exists((string) $json['type']) ? ImageType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
            'version' => isset($json['version']) ? (string) $json['version'] : null,
            'platform' => isset($json['platform']) ? (!Platform::exists((string) $json['platform']) ? Platform::UNKNOWN_TO_SDK : (string) $json['platform']) : null,
            'osVersion' => isset($json['osVersion']) ? (string) $json['osVersion'] : null,
            'state' => empty($json['state']) ? null : $this->populateResultImageState($json['state']),
            'owner' => isset($json['owner']) ? (string) $json['owner'] : null,
            'dateCreated' => isset($json['dateCreated']) ? (string) $json['dateCreated'] : null,
            'outputResources' => empty($json['outputResources']) ? null : $this->populateResultOutputResources($json['outputResources']),
            'tags' => !isset($json['tags']) ? null : $this->populateResultTagMap($json['tags']),
            'buildType' => isset($json['buildType']) ? (!BuildType::exists((string) $json['buildType']) ? BuildType::UNKNOWN_TO_SDK : (string) $json['buildType']) : null,
            'imageSource' => isset($json['imageSource']) ? (!ImageSource::exists((string) $json['imageSource']) ? ImageSource::UNKNOWN_TO_SDK : (string) $json['imageSource']) : null,
            'deprecationTime' => isset($json['deprecationTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['deprecationTime']))) ? $d : null,
            'lifecycleExecutionId' => isset($json['lifecycleExecutionId']) ? (string) $json['lifecycleExecutionId'] : null,
            'loggingConfiguration' => empty($json['loggingConfiguration']) ? null : $this->populateResultImageLoggingConfiguration($json['loggingConfiguration']),
        ]);
    }

    /**
     * @return ImageSummary[]
     */
    private function populateResultImageSummaryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultImageSummary($item);
        }

        return $items;
    }

    private function populateResultOutputResources(array $json): OutputResources
    {
        return new OutputResources([
            'amis' => !isset($json['amis']) ? null : $this->populateResultAmiList($json['amis']),
            'containers' => !isset($json['containers']) ? null : $this->populateResultContainerList($json['containers']),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultStringList(array $json): array
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

    /**
     * @return array<string, string>
     */
    private function populateResultTagMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }
}
