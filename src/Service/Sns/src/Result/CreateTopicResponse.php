<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Response from CreateTopic action.
 */
class CreateTopicResponse extends Result
{
    /**
     * The Amazon Resource Name (ARN) assigned to the created topic.
     *
     * @var string|null
     */
    private $topicArn;

    public function getTopicArn(): ?string
    {
        $this->initialize();

        return $this->topicArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreateTopicResult;

        $this->topicArn = (null !== $v = $data->TopicArn[0]) ? (string) $v : null;
    }
}
