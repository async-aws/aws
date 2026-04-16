<?php

namespace AsyncAws\ImageBuilder\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartImagePipelineExecutionResponse extends Result
{
    /**
     * The request ID that uniquely identifies this request.
     *
     * @var string|null
     */
    private $requestId;

    /**
     * The client token that uniquely identifies the request.
     *
     * @var string|null
     */
    private $clientToken;

    /**
     * The Amazon Resource Name (ARN) of the image that the request created.
     *
     * @var string|null
     */
    private $imageBuildVersionArn;

    public function getClientToken(): ?string
    {
        $this->initialize();

        return $this->clientToken;
    }

    public function getImageBuildVersionArn(): ?string
    {
        $this->initialize();

        return $this->imageBuildVersionArn;
    }

    public function getRequestId(): ?string
    {
        $this->initialize();

        return $this->requestId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->requestId = isset($data['requestId']) ? (string) $data['requestId'] : null;
        $this->clientToken = isset($data['clientToken']) ? (string) $data['clientToken'] : null;
        $this->imageBuildVersionArn = isset($data['imageBuildVersionArn']) ? (string) $data['imageBuildVersionArn'] : null;
    }
}
