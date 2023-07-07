<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the associated CloudHSM cluster did not meet the configuration requirements for an
 * CloudHSM key store.
 *
 * - The CloudHSM cluster must be configured with private subnets in at least two different Availability Zones in the
 *   Region.
 * - The security group for the cluster [^1] (cloudhsm-cluster-*<cluster-id>*-sg) must include inbound rules and
 *   outbound rules that allow TCP traffic on ports 2223-2225. The **Source** in the inbound rules and the
 *   **Destination** in the outbound rules must match the security group ID. These rules are set by default when you
 *   create the CloudHSM cluster. Do not delete or change them. To get information about a particular security group,
 *   use the DescribeSecurityGroups [^2] operation.
 * - The CloudHSM cluster must contain at least as many HSMs as the operation requires. To add HSMs, use the CloudHSM
 *   CreateHsm [^3] operation.
 *
 *   For the CreateCustomKeyStore, UpdateCustomKeyStore, and CreateKey operations, the CloudHSM cluster must have at
 *   least two active HSMs, each in a different Availability Zone. For the ConnectCustomKeyStore operation, the CloudHSM
 *   must contain at least one active HSM.
 *
 * For information about the requirements for an CloudHSM cluster that is associated with an CloudHSM key store, see
 * Assemble the Prerequisites [^4] in the *Key Management Service Developer Guide*. For information about creating a
 * private subnet for an CloudHSM cluster, see Create a Private Subnet [^5] in the *CloudHSM User Guide*. For
 * information about cluster security groups, see Configure a Default Security Group [^6] in the **CloudHSM User
 * Guide**.
 *
 * [^1]: https://docs.aws.amazon.com/cloudhsm/latest/userguide/configure-sg.html
 * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeSecurityGroups.html
 * [^3]: https://docs.aws.amazon.com/cloudhsm/latest/APIReference/API_CreateHsm.html
 * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/create-keystore.html#before-keystore
 * [^5]: https://docs.aws.amazon.com/cloudhsm/latest/userguide/create-subnets.html
 * [^6]: https://docs.aws.amazon.com/cloudhsm/latest/userguide/configure-sg.html
 */
final class CloudHsmClusterInvalidConfigurationException extends ClientException
{
}
