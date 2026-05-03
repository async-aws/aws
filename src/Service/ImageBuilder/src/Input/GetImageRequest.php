<?php

namespace AsyncAws\ImageBuilder\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetImageRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the image that you want to get.
     *
     * @required
     *
     * @var string|null
     */
    private $imageBuildVersionArn;

    /**
     * @param array{
     *   imageBuildVersionArn?: string,
     *   '@region'?: string|null,
     *   '@responseBuffer'?: bool,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->imageBuildVersionArn = $input['imageBuildVersionArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   imageBuildVersionArn?: string,
     *   '@region'?: string|null,
     *   '@responseBuffer'?: bool,
     * }|GetImageRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getImageBuildVersionArn(): ?string
    {
        return $this->imageBuildVersionArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];
        if (null === $v = $this->imageBuildVersionArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "imageBuildVersionArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['imageBuildVersionArn'] = $v;

        // Prepare URI
        $uriString = '/GetImage';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setImageBuildVersionArn(?string $value): self
    {
        $this->imageBuildVersionArn = $value;

        return $this;
    }
}
