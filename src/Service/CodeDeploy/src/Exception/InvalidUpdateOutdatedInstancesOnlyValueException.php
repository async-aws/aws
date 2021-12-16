<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The UpdateOutdatedInstancesOnly value is invalid. For AWS Lambda deployments, `false` is expected. For
 * EC2/On-premises deployments, `true` or `false` is expected.
 */
final class InvalidUpdateOutdatedInstancesOnlyValueException extends ClientException
{
}
