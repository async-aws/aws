<?php

namespace AsyncAws\CodeCommit\Input;

use AsyncAws\CodeCommit\ValueObject\RepositoryTrigger;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a put repository triggers operation.
 */
final class PutRepositoryTriggersInput extends Input
{
    /**
     * The name of the repository where you want to create or update the trigger.
     *
     * @required
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * The JSON block of configuration information for each trigger.
     *
     * @required
     *
     * @var RepositoryTrigger[]|null
     */
    private $triggers;

    /**
     * @param array{
     *   repositoryName?: string,
     *   triggers?: array<RepositoryTrigger|array>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->triggers = isset($input['triggers']) ? array_map([RepositoryTrigger::class, 'create'], $input['triggers']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   repositoryName?: string,
     *   triggers?: array<RepositoryTrigger|array>,
     *   '@region'?: string|null,
     * }|PutRepositoryTriggersInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRepositoryName(): ?string
    {
        return $this->repositoryName;
    }

    /**
     * @return RepositoryTrigger[]
     */
    public function getTriggers(): array
    {
        return $this->triggers ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeCommit_20150413.PutRepositoryTriggers',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setRepositoryName(?string $value): self
    {
        $this->repositoryName = $value;

        return $this;
    }

    /**
     * @param RepositoryTrigger[] $value
     */
    public function setTriggers(array $value): self
    {
        $this->triggers = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->repositoryName) {
            throw new InvalidArgument(sprintf('Missing parameter "repositoryName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['repositoryName'] = $v;
        if (null === $v = $this->triggers) {
            throw new InvalidArgument(sprintf('Missing parameter "triggers" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['triggers'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['triggers'][$index] = $listValue->requestBody();
        }

        return $payload;
    }
}
