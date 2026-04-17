<?php

namespace AsyncAws\ImageBuilder\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteImageResponse extends Result
{
    /**
     * The request ID that uniquely identifies this request.
     *
     * @var string|null
     */
    private $requestId;

    /**
     * The Amazon Resource Name (ARN) of the Image Builder image resource that this request deleted.
     *
     * @var string|null
     */
    private $imageBuildVersionArn;

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
        $this->imageBuildVersionArn = isset($data['imageBuildVersionArn']) ? (string) $data['imageBuildVersionArn'] : null;
    }
}
