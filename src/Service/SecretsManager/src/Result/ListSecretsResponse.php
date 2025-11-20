<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\SecretsManager\Input\ListSecretsRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;
use AsyncAws\SecretsManager\ValueObject\ExternalSecretRotationMetadataItem;
use AsyncAws\SecretsManager\ValueObject\RotationRulesType;
use AsyncAws\SecretsManager\ValueObject\SecretListEntry;
use AsyncAws\SecretsManager\ValueObject\Tag;

/**
 * @implements \IteratorAggregate<SecretListEntry>
 */
class ListSecretsResponse extends Result implements \IteratorAggregate
{
    /**
     * A list of the secrets in the account.
     *
     * @var SecretListEntry[]
     */
    private $secretList;

    /**
     * Secrets Manager includes this value if there's more output available than what is included in the current response.
     * This can occur even when the response includes no values at all, such as when you ask for a filtered view of a long
     * list. To get the next results, call `ListSecrets` again with this value.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over SecretList.
     *
     * @return \Traversable<SecretListEntry>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getSecretList();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<SecretListEntry>
     */
    public function getSecretList(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->secretList;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SecretsManagerClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListSecretsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listSecrets($input));
            } else {
                $nextPage = null;
            }

            yield from $page->secretList;

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

        $this->secretList = empty($data['SecretList']) ? [] : $this->populateResultSecretListType($data['SecretList']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    private function populateResultExternalSecretRotationMetadataItem(array $json): ExternalSecretRotationMetadataItem
    {
        return new ExternalSecretRotationMetadataItem([
            'Key' => isset($json['Key']) ? (string) $json['Key'] : null,
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
        ]);
    }

    /**
     * @return ExternalSecretRotationMetadataItem[]
     */
    private function populateResultExternalSecretRotationMetadataType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultExternalSecretRotationMetadataItem($item);
        }

        return $items;
    }

    private function populateResultRotationRulesType(array $json): RotationRulesType
    {
        return new RotationRulesType([
            'AutomaticallyAfterDays' => isset($json['AutomaticallyAfterDays']) ? (int) $json['AutomaticallyAfterDays'] : null,
            'Duration' => isset($json['Duration']) ? (string) $json['Duration'] : null,
            'ScheduleExpression' => isset($json['ScheduleExpression']) ? (string) $json['ScheduleExpression'] : null,
        ]);
    }

    private function populateResultSecretListEntry(array $json): SecretListEntry
    {
        return new SecretListEntry([
            'ARN' => isset($json['ARN']) ? (string) $json['ARN'] : null,
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Type' => isset($json['Type']) ? (string) $json['Type'] : null,
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'KmsKeyId' => isset($json['KmsKeyId']) ? (string) $json['KmsKeyId'] : null,
            'RotationEnabled' => isset($json['RotationEnabled']) ? filter_var($json['RotationEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'RotationLambdaARN' => isset($json['RotationLambdaARN']) ? (string) $json['RotationLambdaARN'] : null,
            'RotationRules' => empty($json['RotationRules']) ? null : $this->populateResultRotationRulesType($json['RotationRules']),
            'ExternalSecretRotationMetadata' => !isset($json['ExternalSecretRotationMetadata']) ? null : $this->populateResultExternalSecretRotationMetadataType($json['ExternalSecretRotationMetadata']),
            'ExternalSecretRotationRoleArn' => isset($json['ExternalSecretRotationRoleArn']) ? (string) $json['ExternalSecretRotationRoleArn'] : null,
            'LastRotatedDate' => (isset($json['LastRotatedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastRotatedDate'])))) ? $d : null,
            'LastChangedDate' => (isset($json['LastChangedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastChangedDate'])))) ? $d : null,
            'LastAccessedDate' => (isset($json['LastAccessedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastAccessedDate'])))) ? $d : null,
            'DeletedDate' => (isset($json['DeletedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['DeletedDate'])))) ? $d : null,
            'NextRotationDate' => (isset($json['NextRotationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['NextRotationDate'])))) ? $d : null,
            'Tags' => !isset($json['Tags']) ? null : $this->populateResultTagListType($json['Tags']),
            'SecretVersionsToStages' => !isset($json['SecretVersionsToStages']) ? null : $this->populateResultSecretVersionsToStagesMapType($json['SecretVersionsToStages']),
            'OwningService' => isset($json['OwningService']) ? (string) $json['OwningService'] : null,
            'CreatedDate' => (isset($json['CreatedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreatedDate'])))) ? $d : null,
            'PrimaryRegion' => isset($json['PrimaryRegion']) ? (string) $json['PrimaryRegion'] : null,
        ]);
    }

    /**
     * @return SecretListEntry[]
     */
    private function populateResultSecretListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSecretListEntry($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultSecretVersionStagesType(array $json): array
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
     * @return array<string, string[]>
     */
    private function populateResultSecretVersionsToStagesMapType(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultSecretVersionStagesType($value);
        }

        return $items;
    }

    private function populateResultTag(array $json): Tag
    {
        return new Tag([
            'Key' => isset($json['Key']) ? (string) $json['Key'] : null,
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }
}
