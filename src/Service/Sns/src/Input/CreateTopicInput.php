<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sns\ValueObject\Tag;

/**
 * Input for CreateTopic action.
 */
final class CreateTopicInput extends Input
{
    /**
     * The name of the topic you want to create.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * The list of tags to add to a new topic.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * @param array{
     *   Name?: string,
     *   Attributes?: array<string, string>,
     *   Tags?: Tag[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->name = $input['Name'] ?? null;
        $this->attributes = $input['Attributes'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Tag[]
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
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'CreateTopic', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, string> $value
     */
    public function setAttributes(array $value): self
    {
        $this->attributes = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

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

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->attributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["Attributes.entry.$index.key"] = $mapKey;
                $payload["Attributes.entry.$index.value"] = $mapValue;
            }
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
