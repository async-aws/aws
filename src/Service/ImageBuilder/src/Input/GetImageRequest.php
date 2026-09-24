<?php

namespace AsyncAws\ImageBuilder\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetImageRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the image that you want to get. You can specify a full build version ARN, or a
     * version ARN with or without wildcards (`x.x.x`, `1.x.x`, or `1.0.x`). A version or wildcard ARN resolves to the
     * latest matching build version that has reached `AVAILABLE` status. Builds that were later deprecated, disabled, or
     * deleted don't resolve. To get an image in any other state, such as a failed or in-progress build, specify the full
     * build version ARN.
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
