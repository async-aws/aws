<?php

namespace AsyncAws\CodeCommit\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a create repository operation.
 */
final class CreateRepositoryInput extends Input
{
    /**
     * The name of the new repository to be created.
     *
     * > The repository name must be unique across the calling AWS account. Repository names are limited to 100
     * > alphanumeric, dash, and underscore characters, and cannot include certain characters. For more information about
     * > the limits on repository names, see Limits [^1] in the *AWS CodeCommit User Guide*. The suffix .git is prohibited.
     *
     * [^1]: https://docs.aws.amazon.com/codecommit/latest/userguide/limits.html
     *
     * @required
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * A comment or description about the new repository.
     *
     * > The description field for a repository accepts all HTML characters and all valid Unicode characters. Applications
     * > that do not HTML-encode the description and display it in a webpage can expose users to potentially malicious code.
     * > Make sure that you HTML-encode the description field in any application that uses this API to display the
     * > repository description on a webpage.
     *
     * @var string|null
     */
    private $repositoryDescription;

    /**
     * One or more tag key-value pairs to use when tagging this repository.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   repositoryName?: string,
     *   repositoryDescription?: null|string,
     *   tags?: null|array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->repositoryDescription = $input['repositoryDescription'] ?? null;
        $this->tags = $input['tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   repositoryName?: string,
     *   repositoryDescription?: null|string,
     *   tags?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|CreateRepositoryInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRepositoryDescription(): ?string
    {
        return $this->repositoryDescription;
    }

    public function getRepositoryName(): ?string
    {
        return $this->repositoryName;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeCommit_20150413.CreateRepository',
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

    public function setRepositoryDescription(?string $value): self
    {
        $this->repositoryDescription = $value;

        return $this;
    }

    public function setRepositoryName(?string $value): self
    {
        $this->repositoryName = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->repositoryName) {
            throw new InvalidArgument(sprintf('Missing parameter "repositoryName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['repositoryName'] = $v;
        if (null !== $v = $this->repositoryDescription) {
            $payload['repositoryDescription'] = $v;
        }
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['tags'] = new \stdClass();
            } else {
                $payload['tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['tags'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
