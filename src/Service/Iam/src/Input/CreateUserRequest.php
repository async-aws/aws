<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Iam\ValueObject\Tag;

final class CreateUserRequest extends Input
{
    /**
     * The path for the user name. For more information about paths, see IAM identifiers in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     *
     * @var string|null
     */
    private $path;

    /**
     * The name of the user to create.
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * The ARN of the policy that is used to set the permissions boundary for the user.
     *
     * @var string|null
     */
    private $permissionsBoundary;

    /**
     * A list of tags that you want to attach to the new user. Each tag consists of a key name and an associated value. For
     * more information about tagging, see Tagging IAM resources in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_tags.html
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * @param array{
     *   Path?: string,
     *   UserName?: string,
     *   PermissionsBoundary?: string,
     *   Tags?: Tag[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->path = $input['Path'] ?? null;
        $this->userName = $input['UserName'] ?? null;
        $this->permissionsBoundary = $input['PermissionsBoundary'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getPermissionsBoundary(): ?string
    {
        return $this->permissionsBoundary;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'CreateUser', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setPath(?string $value): self
    {
        $this->path = $value;

        return $this;
    }

    public function setPermissionsBoundary(?string $value): self
    {
        $this->permissionsBoundary = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    public function setUserName(?string $value): self
    {
        $this->userName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->path) {
            $payload['Path'] = $v;
        }
        if (null === $v = $this->userName) {
            throw new InvalidArgument(sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserName'] = $v;
        if (null !== $v = $this->permissionsBoundary) {
            $payload['PermissionsBoundary'] = $v;
        }
        if (null !== $v = $this->tags) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["Tags.member.$index.$bodyKey"] = $bodyValue;
                }
            }
        }

        return $payload;
    }
}
