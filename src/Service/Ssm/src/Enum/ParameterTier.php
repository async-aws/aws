<?php

namespace AsyncAws\Ssm\Enum;

/**
 * The parameter tier to assign to a parameter.
 * Parameter Store offers a standard tier and an advanced tier for parameters. Standard parameters have a content size
 * limit of 4 KB and can't be configured to use parameter policies. You can create a maximum of 10,000 standard
 * parameters for each Region in an AWS account. Standard parameters are offered at no additional cost.
 * Advanced parameters have a content size limit of 8 KB and can be configured to use parameter policies. You can create
 * a maximum of 100,000 advanced parameters for each Region in an AWS account. Advanced parameters incur a charge. For
 * more information, see Standard and advanced parameter tiers in the *AWS Systems Manager User Guide*.
 * You can change a standard parameter to an advanced parameter any time. But you can't revert an advanced parameter to
 * a standard parameter. Reverting an advanced parameter to a standard parameter would result in data loss because the
 * system would truncate the size of the parameter from 8 KB to 4 KB. Reverting would also remove any policies attached
 * to the parameter. Lastly, advanced parameters use a different form of encryption than standard parameters.
 * If you no longer need an advanced parameter, or if you no longer want to incur charges for an advanced parameter, you
 * must delete it and recreate it as a new standard parameter.
 * **Using the Default Tier Configuration**
 * In `PutParameter` requests, you can specify the tier to create the parameter in. Whenever you specify a tier in the
 * request, Parameter Store creates or updates the parameter according to that request. However, if you do not specify a
 * tier in a request, Parameter Store assigns the tier based on the current Parameter Store default tier configuration.
 * The default tier when you begin using Parameter Store is the standard-parameter tier. If you use the
 * advanced-parameter tier, you can specify one of the following as the default:.
 *
 * - **Advanced**: With this option, Parameter Store evaluates all requests as advanced parameters.
 * - **Intelligent-Tiering**: With this option, Parameter Store evaluates each request to determine if the parameter is
 *   standard or advanced.
 *   If the request doesn't include any options that require an advanced parameter, the parameter is created in the
 *   standard-parameter tier. If one or more options requiring an advanced parameter are included in the request,
 *   Parameter Store create a parameter in the advanced-parameter tier.
 *   This approach helps control your parameter-related costs by always creating standard parameters unless an advanced
 *   parameter is necessary.
 *
 * Options that require an advanced parameter include the following:
 *
 * - The content size of the parameter is more than 4 KB.
 * - The parameter uses a parameter policy.
 * - More than 10,000 parameters already exist in your AWS account in the current Region.
 *
 * For more information about configuring the default tier option, see Specifying a default parameter tier in the *AWS
 * Systems Manager User Guide*.
 *
 * @see https://docs.aws.amazon.com/systems-manager/latest/userguide/parameter-store-advanced-parameters.html
 * @see https://docs.aws.amazon.com/systems-manager/latest/userguide/ps-default-tier.html
 */
final class ParameterTier
{
    public const ADVANCED = 'Advanced';
    public const INTELLIGENT_TIERING = 'Intelligent-Tiering';
    public const STANDARD = 'Standard';

    public static function exists(string $value): bool
    {
        return isset([
            self::ADVANCED => true,
            self::INTELLIGENT_TIERING => true,
            self::STANDARD => true,
        ][$value]);
    }
}
