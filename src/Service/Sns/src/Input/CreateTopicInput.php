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
     * @var string[]
     */
    private $Attributes;

    /**
     * The list of tags to add to a new topic.
     *
     * @var Tag[]
     */
    private $Tags;

    /**
     * @param array{
     *   Name?: string,
     *   Attributes?: string[],
     *   Tags?: \AsyncAws\Sns\ValueObject\Tag[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Name = $input['Name'] ?? null;
        $this->Attributes = $input['Attributes'] ?? [];
        $this->Tags = array_map([Tag::class, 'create'], $input['Tags'] ?? []);
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->Attributes;
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
        return $this->Tags;
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
     * @param string[] $value
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

        $index = 0;
        foreach ($this->Attributes as $mapKey => $mapValue) {
            ++$index;
            $payload["Attributes.entry.$index.key"] = $mapKey;
            $payload["Attributes.entry.$index.value"] = $mapValue;
        }

        $index = 0;
        foreach ($this->Tags as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["Tags.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        return $payload;
    }
}
