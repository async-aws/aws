<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Lambda\Enum\FunctionVersion;

final class ListFunctionsRequest extends Input
{
    /**
     * For Lambda@Edge functions, the AWS Region of the master function. For example, `us-east-1` filters the list of
     * functions to only include Lambda@Edge functions replicated from a master function in US East (N. Virginia). If
     * specified, you must set `FunctionVersion` to `ALL`.
     *
     * @var string|null
     */
    private $masterRegion;

    /**
     * Set to `ALL` to include entries for all published versions of each function.
     *
     * @var null|FunctionVersion::*
     */
    private $functionVersion;

    /**
     * Specify the pagination token that's returned by a previous request to retrieve the next page of results.
     *
     * @var string|null
     */
    private $marker;

    /**
     * The maximum number of functions to return in the response. Note that `ListFunctions` returns a maximum of 50 items in
     * each response, even if you set the number higher.
     *
     * @var int|null
     */
    private $maxItems;

    /**
     * @param array{
     *   MasterRegion?: string,
     *   FunctionVersion?: FunctionVersion::*,
     *   Marker?: string,
     *   MaxItems?: int,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->masterRegion = $input['MasterRegion'] ?? null;
        $this->functionVersion = $input['FunctionVersion'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FunctionVersion::*|null
     */
    public function getFunctionVersion(): ?string
    {
        return $this->functionVersion;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMasterRegion(): ?string
    {
        return $this->masterRegion;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];
        if (null !== $this->masterRegion) {
            $query['MasterRegion'] = $this->masterRegion;
        }
        if (null !== $this->functionVersion) {
            if (!FunctionVersion::exists($this->functionVersion)) {
                throw new InvalidArgument(sprintf('Invalid parameter "FunctionVersion" for "%s". The value "%s" is not a valid "FunctionVersion".', __CLASS__, $this->functionVersion));
            }
            $query['FunctionVersion'] = $this->functionVersion;
        }
        if (null !== $this->marker) {
            $query['Marker'] = $this->marker;
        }
        if (null !== $this->maxItems) {
            $query['MaxItems'] = (string) $this->maxItems;
        }

        // Prepare URI
        $uriString = '/2015-03-31/functions/';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param FunctionVersion::*|null $value
     */
    public function setFunctionVersion(?string $value): self
    {
        $this->functionVersion = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    public function setMasterRegion(?string $value): self
    {
        $this->masterRegion = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->maxItems = $value;

        return $this;
    }
}
