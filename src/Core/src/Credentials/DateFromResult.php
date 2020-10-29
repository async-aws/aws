<?php

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Result;

/**
 * @internal
 */
trait DateFromResult
{
    private function getDateFromResult(Result $result): ?\DateTimeImmutable
    {
        if (
            (null !== $response = $result->info()['response'] ?? null) &&
            (null !== $date = $response->getHeaders(false)['date'][0] ?? null)
        ) {
            return new \DateTimeImmutable($date);
        }

        return null;
    }
}
