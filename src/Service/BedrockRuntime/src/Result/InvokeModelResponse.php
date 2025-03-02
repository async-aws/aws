<?php

namespace AsyncAws\BedrockRuntime\Result;

use AsyncAws\BedrockRuntime\Enum\PerformanceConfigLatency;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class InvokeModelResponse extends Result
{
    /**
     * Inference response from the model in the format specified in the `contentType` header. To see the format and content
     * of the request and response bodies for different models, refer to Inference parameters [^1].
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
     *
     * @var string
     */
    private $body;

    /**
     * The MIME type of the inference result.
     *
     * @var string
     */
    private $contentType;

    /**
     * Model performance settings for the request.
     *
     * @var PerformanceConfigLatency::*|null
     */
    private $performanceConfigLatency;

    public function getBody(): string
    {
        $this->initialize();

        return $this->body;
    }

    public function getContentType(): string
    {
        $this->initialize();

        return $this->contentType;
    }

    /**
     * @return PerformanceConfigLatency::*|null
     */
    public function getPerformanceConfigLatency(): ?string
    {
        $this->initialize();

        return $this->performanceConfigLatency;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->contentType = $headers['content-type'][0];
        $this->performanceConfigLatency = $headers['x-amzn-bedrock-performanceconfig-latency'][0] ?? null;

        $this->body = $response->getContent();
    }
}
