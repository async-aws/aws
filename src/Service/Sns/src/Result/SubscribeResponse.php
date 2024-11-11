<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Response for Subscribe action.
 */
class SubscribeResponse extends Result
{
    /**
     * The ARN of the subscription if it is confirmed, or the string "pending confirmation" if the subscription requires
     * confirmation. However, if the API request parameter `ReturnSubscriptionArn` is true, then the value is always the
     * subscription ARN, even if the subscription requires confirmation.
     *
     * @var string|null
     */
    private $subscriptionArn;

    public function getSubscriptionArn(): ?string
    {
        $this->initialize();

        return $this->subscriptionArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->SubscribeResult;

        $this->subscriptionArn = (null !== $v = $data->SubscriptionArn[0]) ? (string) $v : null;
    }
}
