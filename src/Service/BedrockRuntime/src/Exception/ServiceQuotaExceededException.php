<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Your request exceeds the service quota for your account. You can view your quotas at Viewing service quotas [^1]. You
 * can resubmit your request later.
 *
 * [^1]: https://docs.aws.amazon.com/servicequotas/latest/userguide/gs-request-quota.html
 */
final class ServiceQuotaExceededException extends ClientException
{
}
