<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\ValueObject\DeadLetterConfig;
use AsyncAws\Lambda\ValueObject\EnvironmentError;
use AsyncAws\Lambda\ValueObject\EnvironmentResponse;
use AsyncAws\Lambda\ValueObject\FileSystemConfig;
use AsyncAws\Lambda\ValueObject\FunctionConfiguration;
use AsyncAws\Lambda\ValueObject\ImageConfig;
use AsyncAws\Lambda\ValueObject\ImageConfigError;
use AsyncAws\Lambda\ValueObject\ImageConfigResponse;
use AsyncAws\Lambda\ValueObject\Layer;
use AsyncAws\Lambda\ValueObject\TracingConfigResponse;
use AsyncAws\Lambda\ValueObject\VpcConfigResponse;

/**
 * @implements \IteratorAggregate<FunctionConfiguration>
 */
class ListVersionsByFunctionResponse extends Result implements \IteratorAggregate
{
    /**
     * The pagination token that's included if more results are available.
     */
    private $nextMarker;

    /**
     * A list of Lambda function versions.
     */
    private $versions = [];

    /**
     * Iterates over Versions.
     *
     * @return \Traversable<FunctionConfiguration>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListVersionsByFunctionRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextMarker()) {
                $input->setMarker($page->getNextMarker());

                $this->registerPrefetch($nextPage = $client->ListVersionsByFunction($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getVersions(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->nextMarker;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<FunctionConfiguration>
     */
    public function getVersions(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->versions;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListVersionsByFunctionRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextMarker()) {
                $input->setMarker($page->getNextMarker());

                $this->registerPrefetch($nextPage = $client->ListVersionsByFunction($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getVersions(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextMarker = isset($data['NextMarker']) ? (string) $data['NextMarker'] : null;
        $this->versions = empty($data['Versions']) ? [] : $this->populateResultFunctionList($data['Versions']);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultEnvironmentVariables(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    /**
     * @return FileSystemConfig[]
     */
    private function populateResultFileSystemConfigList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new FileSystemConfig([
                'Arn' => (string) $item['Arn'],
                'LocalMountPath' => (string) $item['LocalMountPath'],
            ]);
        }

        return $items;
    }

    /**
     * @return FunctionConfiguration[]
     */
    private function populateResultFunctionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new FunctionConfiguration([
                'FunctionName' => isset($item['FunctionName']) ? (string) $item['FunctionName'] : null,
                'FunctionArn' => isset($item['FunctionArn']) ? (string) $item['FunctionArn'] : null,
                'Runtime' => isset($item['Runtime']) ? (string) $item['Runtime'] : null,
                'Role' => isset($item['Role']) ? (string) $item['Role'] : null,
                'Handler' => isset($item['Handler']) ? (string) $item['Handler'] : null,
                'CodeSize' => isset($item['CodeSize']) ? (string) $item['CodeSize'] : null,
                'Description' => isset($item['Description']) ? (string) $item['Description'] : null,
                'Timeout' => isset($item['Timeout']) ? (int) $item['Timeout'] : null,
                'MemorySize' => isset($item['MemorySize']) ? (int) $item['MemorySize'] : null,
                'LastModified' => isset($item['LastModified']) ? (string) $item['LastModified'] : null,
                'CodeSha256' => isset($item['CodeSha256']) ? (string) $item['CodeSha256'] : null,
                'Version' => isset($item['Version']) ? (string) $item['Version'] : null,
                'VpcConfig' => empty($item['VpcConfig']) ? null : new VpcConfigResponse([
                    'SubnetIds' => empty($item['VpcConfig']['SubnetIds']) ? [] : $this->populateResultSubnetIds($item['VpcConfig']['SubnetIds']),
                    'SecurityGroupIds' => empty($item['VpcConfig']['SecurityGroupIds']) ? [] : $this->populateResultSecurityGroupIds($item['VpcConfig']['SecurityGroupIds']),
                    'VpcId' => isset($item['VpcConfig']['VpcId']) ? (string) $item['VpcConfig']['VpcId'] : null,
                ]),
                'DeadLetterConfig' => empty($item['DeadLetterConfig']) ? null : new DeadLetterConfig([
                    'TargetArn' => isset($item['DeadLetterConfig']['TargetArn']) ? (string) $item['DeadLetterConfig']['TargetArn'] : null,
                ]),
                'Environment' => empty($item['Environment']) ? null : new EnvironmentResponse([
                    'Variables' => empty($item['Environment']['Variables']) ? [] : $this->populateResultEnvironmentVariables($item['Environment']['Variables']),
                    'Error' => empty($item['Environment']['Error']) ? null : new EnvironmentError([
                        'ErrorCode' => isset($item['Environment']['Error']['ErrorCode']) ? (string) $item['Environment']['Error']['ErrorCode'] : null,
                        'Message' => isset($item['Environment']['Error']['Message']) ? (string) $item['Environment']['Error']['Message'] : null,
                    ]),
                ]),
                'KMSKeyArn' => isset($item['KMSKeyArn']) ? (string) $item['KMSKeyArn'] : null,
                'TracingConfig' => empty($item['TracingConfig']) ? null : new TracingConfigResponse([
                    'Mode' => isset($item['TracingConfig']['Mode']) ? (string) $item['TracingConfig']['Mode'] : null,
                ]),
                'MasterArn' => isset($item['MasterArn']) ? (string) $item['MasterArn'] : null,
                'RevisionId' => isset($item['RevisionId']) ? (string) $item['RevisionId'] : null,
                'Layers' => empty($item['Layers']) ? [] : $this->populateResultLayersReferenceList($item['Layers']),
                'State' => isset($item['State']) ? (string) $item['State'] : null,
                'StateReason' => isset($item['StateReason']) ? (string) $item['StateReason'] : null,
                'StateReasonCode' => isset($item['StateReasonCode']) ? (string) $item['StateReasonCode'] : null,
                'LastUpdateStatus' => isset($item['LastUpdateStatus']) ? (string) $item['LastUpdateStatus'] : null,
                'LastUpdateStatusReason' => isset($item['LastUpdateStatusReason']) ? (string) $item['LastUpdateStatusReason'] : null,
                'LastUpdateStatusReasonCode' => isset($item['LastUpdateStatusReasonCode']) ? (string) $item['LastUpdateStatusReasonCode'] : null,
                'FileSystemConfigs' => empty($item['FileSystemConfigs']) ? [] : $this->populateResultFileSystemConfigList($item['FileSystemConfigs']),
                'PackageType' => isset($item['PackageType']) ? (string) $item['PackageType'] : null,
                'ImageConfigResponse' => empty($item['ImageConfigResponse']) ? null : new ImageConfigResponse([
                    'ImageConfig' => empty($item['ImageConfigResponse']['ImageConfig']) ? null : new ImageConfig([
                        'EntryPoint' => empty($item['ImageConfigResponse']['ImageConfig']['EntryPoint']) ? [] : $this->populateResultStringList($item['ImageConfigResponse']['ImageConfig']['EntryPoint']),
                        'Command' => empty($item['ImageConfigResponse']['ImageConfig']['Command']) ? [] : $this->populateResultStringList($item['ImageConfigResponse']['ImageConfig']['Command']),
                        'WorkingDirectory' => isset($item['ImageConfigResponse']['ImageConfig']['WorkingDirectory']) ? (string) $item['ImageConfigResponse']['ImageConfig']['WorkingDirectory'] : null,
                    ]),
                    'Error' => empty($item['ImageConfigResponse']['Error']) ? null : new ImageConfigError([
                        'ErrorCode' => isset($item['ImageConfigResponse']['Error']['ErrorCode']) ? (string) $item['ImageConfigResponse']['Error']['ErrorCode'] : null,
                        'Message' => isset($item['ImageConfigResponse']['Error']['Message']) ? (string) $item['ImageConfigResponse']['Error']['Message'] : null,
                    ]),
                ]),
                'SigningProfileVersionArn' => isset($item['SigningProfileVersionArn']) ? (string) $item['SigningProfileVersionArn'] : null,
                'SigningJobArn' => isset($item['SigningJobArn']) ? (string) $item['SigningJobArn'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return Layer[]
     */
    private function populateResultLayersReferenceList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Layer([
                'Arn' => isset($item['Arn']) ? (string) $item['Arn'] : null,
                'CodeSize' => isset($item['CodeSize']) ? (string) $item['CodeSize'] : null,
                'SigningProfileVersionArn' => isset($item['SigningProfileVersionArn']) ? (string) $item['SigningProfileVersionArn'] : null,
                'SigningJobArn' => isset($item['SigningJobArn']) ? (string) $item['SigningJobArn'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultSecurityGroupIds(array $json): array
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
     * @return string[]
     */
    private function populateResultSubnetIds(array $json): array
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
