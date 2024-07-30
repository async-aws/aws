<?php

namespace AsyncAws\Comprehend\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DetectDominantLanguageRequest extends Input
{
    /**
     * A UTF-8 text string. The string must contain at least 20 characters. The maximum string size is 100 KB.
     *
     * @required
     *
     * @var string|null
     */
    private $text;

    /**
     * @param array{
     *   Text?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->text = $input['Text'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Text?: string,
     *   '@region'?: string|null,
     * }|DetectDominantLanguageRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Comprehend_20171127.DetectDominantLanguage',
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

    public function setText(?string $value): self
    {
        $this->text = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->text) {
            throw new InvalidArgument(\sprintf('Missing parameter "Text" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Text'] = $v;

        return $payload;
    }
}
