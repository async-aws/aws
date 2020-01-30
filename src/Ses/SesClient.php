<?php

declare(strict_types=1);

namespace AsyncAws\Ses;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\ResultPromise;
use AsyncAws\Aws\Ses\Result\SendEmailResult;

class SesClient extends AbstractApi
{
    /**
     * @return ResultPromise<SendEmailResult>
     */
    public function sendEmail(array $body, array $headers = []): ResultPromise
    {
        $response = $this->getResponse('POST', $body, $headers);

        return new ResultPromise($response, SendEmailResult::class);
    }

    protected function getServiceCode(): string
    {
        return 'ses';
    }
}
