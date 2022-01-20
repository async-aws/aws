<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because the associated CloudHSM cluster did not meet the configuration requirements for a
 * custom key store.
 *
 * - The cluster must be configured with private subnets in at least two different Availability Zones in the Region.
 * - The security group for the cluster (cloudhsm-cluster-*&lt;cluster-id&gt;*-sg) must include inbound rules and
 *   outbound rules that allow TCP traffic on ports 2223-2225. The **Source** in the inbound rules and the
 *   **Destination** in the outbound rules must match the security group ID. These rules are set by default when you
 *   create the cluster. Do not delete or change them. To get information about a particular security group, use the
 *   DescribeSecurityGroups operation.
 * - The cluster must contain at least as many HSMs as the operation requires. To add HSMs, use the CloudHSM CreateHsm
 *   operation.
 *   For the CreateCustomKeyStore, UpdateCustomKeyStore, and CreateKey operations, the CloudHSM cluster must have at
 *   least two active HSMs, each in a different Availability Zone. For the ConnectCustomKeyStore operation, the CloudHSM
 *   must contain at least one active HSM.
 *
 * For information about the requirements for an CloudHSM cluster that is associated with a custom key store, see
 * Assemble the Prerequisites in the *Key Management Service Developer Guide*. For information about creating a private
 * subnet for an CloudHSM cluster, see Create a Private Subnet in the *CloudHSM User Guide*. For information about
 * cluster security groups, see Configure a Default Security Group in the **CloudHSM User Guide**.
 *
 * @see https://docs.aws.amazon.com/cloudhsm/latest/userguide/configure-sg.html
 * @see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeSecurityGroups.html
 * @see https://docs.aws.amazon.com/cloudhsm/latest/APIReference/API_CreateHsm.html
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/create-keystore.html#before-keystore
 * @see https://docs.aws.amazon.com/cloudhsm/latest/userguide/create-subnets.html
 * @see https://docs.aws.amazon.com/cloudhsm/latest/userguide/configure-sg.html
 */
final class CloudHsmClusterInvalidConfigurationException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
