<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified key policy isn't syntactically or semantically correct.
 */
final class KmsOptInRequiredException extends ClientException
{
}
