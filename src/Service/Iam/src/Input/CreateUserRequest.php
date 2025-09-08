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
     * The path for the user name. For more information about paths, see IAM identifiers [^1] in the *IAM User Guide*.
     *
     * This parameter is optional. If it is not included, it defaults to a slash (/).
     *
     * This parameter allows (through its regex pattern [^2]) a string of characters consisting of either a forward slash
     * (/) by itself or a string that must begin and end with forward slashes. In addition, it can contain any ASCII
     * character from the ! (`\u0021`) through the DEL character (`\u007F`), including most punctuation characters, digits,
     * and upper and lowercased letters.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     * [^2]: http://wikipedia.org/wiki/regex
     *
     * @var string|null
     */
    private $path;

    /**
     * The name of the user to create.
     *
     * IAM user, group, role, and policy names must be unique within the account. Names are not distinguished by case. For
     * example, you cannot create resources named both "MyResource" and "myresource".
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * The ARN of the managed policy that is used to set the permissions boundary for the user.
     *
     * A permissions boundary policy defines the maximum permissions that identity-based policies can grant to an entity,
     * but does not grant permissions. Permissions boundaries do not define the maximum permissions that a resource-based
     * policy can grant to an entity. To learn more, see Permissions boundaries for IAM entities [^1] in the *IAM User
     * Guide*.
     *
     * For more information about policy types, see Policy types [^2] in the *IAM User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/access_policies_boundaries.html
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/access_policies.html#access_policy-types
     *
     * @var string|null
     */
    private $permissionsBoundary;

    /**
     * A list of tags that you want to attach to the new user. Each tag consists of a key name and an associated value. For
     * more information about tagging, see Tagging IAM resources [^1] in the *IAM User Guide*.
     *
     * > If any one of the tags is invalid or if you exceed the allowed maximum number of tags, then the entire request
     * > fails and the resource is not created.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_tags.html
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * @param array{
     *   Path?: string|null,
     *   UserName?: string,
     *   PermissionsBoundary?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   '@region'?: string|null,
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

    /**
     * @param array{
     *   Path?: string|null,
     *   UserName?: string,
     *   PermissionsBoundary?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   '@region'?: string|null,
     * }|CreateUserRequest $input
     */
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
            throw new InvalidArgument(\sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
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
