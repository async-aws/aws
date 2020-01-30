<?php

declare(strict_types=1);

namespace AsyncAws\Ses;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\Result;
use AsyncAws\Aws\Ses\Result\SendEmailResult;

class SesClient extends AbstractApi
{
    /**
     * @return Result<SendEmailResult>
     */
    public function sendEmail(array $body, array $headers = []): Result
    {
        $response = $this->getResponse('POST', $body, $headers);

        return new Result($response, SendEmailResult::class);
    }

    protected function getServiceCode(): string
    {
        return 'ses';
    }
}
