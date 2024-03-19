<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Parameter Store retains the 100 most recently created versions of a parameter. After this number of versions has been
 * created, Parameter Store deletes the oldest version when a new one is created. However, if the oldest version has a
 * *label* attached to it, Parameter Store won't delete the version and instead presents this error message:
 *
 * `An error occurred (ParameterMaxVersionLimitExceeded) when calling the PutParameter operation: You attempted to
 * create a new version of *parameter-name* by calling the PutParameter API with the overwrite flag. Version
 * *version-number*, the oldest version, can't be deleted because it has a label associated with it. Move the label to
 * another version of the parameter, and try again.`
 *
 * This safeguard is to prevent parameter versions with mission critical labels assigned to them from being deleted. To
 * continue creating new parameters, first move the label from the oldest version of the parameter to a newer one for
 * use in your operations. For information about moving parameter labels, see Move a parameter label (console) [^1] or
 * Move a parameter label (CLI) [^2] in the *Amazon Web Services Systems Manager User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/systems-manager/latest/userguide/sysman-paramstore-labels.html#sysman-paramstore-labels-console-move
 * [^2]: https://docs.aws.amazon.com/systems-manager/latest/userguide/sysman-paramstore-labels.html#sysman-paramstore-labels-cli-move
 */
final class ParameterMaxVersionLimitExceededException extends ClientException
{
}
