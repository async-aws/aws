<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sns\ValueObject\Tag;

final class CreateTopicInput extends Input
{
    /**
     * The name of the topic you want to create.
     *
     * @required
     *
     * @var string|null
     */
    private $Name;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var array<string, string>|null
     */
    private $Attributes;

    /**
     * The list of tags to add to a new topic.
     *
     * @var Tag[]|null
     */
    private $Tags;

    /**
     * @param array{
     *   Name?: string,
     *   Attributes?: array<string, string>,
     *   Tags?: \AsyncAws\Sns\ValueObject\Tag[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Name = $input['Name'] ?? null;
        $this->Attributes = $input['Attributes'] ?? null;
        $this->Tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
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
        return $this->Attributes ?? [];
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags ?? [];
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
        $this->Attributes = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->Name = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->Tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->Attributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["Attributes.entry.$index.key"] = $mapKey;
                $payload["Attributes.entry.$index.value"] = $mapValue;
            }
        }
        if (null !== $v = $this->Tags) {
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
