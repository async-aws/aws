<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\SecretsManager\Input\ListSecretsRequest;
use AsyncAws\SecretsManager\SecretsManagerClient;
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
     */
    private $secretList;

    /**
     * Secrets Manager includes this value if there's more output available than what is included in the current response.
     * This can occur even when the response includes no values at all, such as when you ask for a filtered view of a long
     * list. To get the next results, call `ListSecrets` again with this value.
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
            if ($page->nextToken) {
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

    /**
     * @return SecretListEntry[]
     */
    private function populateResultSecretListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new SecretListEntry([
                'ARN' => isset($item['ARN']) ? (string) $item['ARN'] : null,
                'Name' => isset($item['Name']) ? (string) $item['Name'] : null,
                'Description' => isset($item['Description']) ? (string) $item['Description'] : null,
                'KmsKeyId' => isset($item['KmsKeyId']) ? (string) $item['KmsKeyId'] : null,
                'RotationEnabled' => isset($item['RotationEnabled']) ? filter_var($item['RotationEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                'RotationLambdaARN' => isset($item['RotationLambdaARN']) ? (string) $item['RotationLambdaARN'] : null,
                'RotationRules' => empty($item['RotationRules']) ? null : new RotationRulesType([
                    'AutomaticallyAfterDays' => isset($item['RotationRules']['AutomaticallyAfterDays']) ? (string) $item['RotationRules']['AutomaticallyAfterDays'] : null,
                    'Duration' => isset($item['RotationRules']['Duration']) ? (string) $item['RotationRules']['Duration'] : null,
                    'ScheduleExpression' => isset($item['RotationRules']['ScheduleExpression']) ? (string) $item['RotationRules']['ScheduleExpression'] : null,
                ]),
                'LastRotatedDate' => (isset($item['LastRotatedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['LastRotatedDate'])))) ? $d : null,
                'LastChangedDate' => (isset($item['LastChangedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['LastChangedDate'])))) ? $d : null,
                'LastAccessedDate' => (isset($item['LastAccessedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['LastAccessedDate'])))) ? $d : null,
                'DeletedDate' => (isset($item['DeletedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['DeletedDate'])))) ? $d : null,
                'Tags' => !isset($item['Tags']) ? null : $this->populateResultTagListType($item['Tags']),
                'SecretVersionsToStages' => !isset($item['SecretVersionsToStages']) ? null : $this->populateResultSecretVersionsToStagesMapType($item['SecretVersionsToStages']),
                'OwningService' => isset($item['OwningService']) ? (string) $item['OwningService'] : null,
                'CreatedDate' => (isset($item['CreatedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['CreatedDate'])))) ? $d : null,
                'PrimaryRegion' => isset($item['PrimaryRegion']) ? (string) $item['PrimaryRegion'] : null,
            ]);
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

    /**
     * @return Tag[]
     */
    private function populateResultTagListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Tag([
                'Key' => isset($item['Key']) ? (string) $item['Key'] : null,
                'Value' => isset($item['Value']) ? (string) $item['Value'] : null,
            ]);
        }

        return $items;
    }
}
