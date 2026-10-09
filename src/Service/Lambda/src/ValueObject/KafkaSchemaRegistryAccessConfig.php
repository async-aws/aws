<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\KafkaSchemaRegistryAuthType;

/**
 * Specific access configuration settings that tell Lambda how to authenticate with your schema registry.
 *
 * If you're working with an Glue schema registry, don't provide authentication details in this object. Instead, ensure
 * that your execution role has the required permissions for Lambda to access your cluster.
 *
 * If you're working with a Confluent schema registry, choose the authentication method in the `Type` field, and provide
 * the Secrets Manager secret ARN in the `URI` field.
 */
final class KafkaSchemaRegistryAccessConfig
{
    /**
     * The type of authentication Lambda uses to access your schema registry.
     *
     * - `BASIC_AUTH` – The Secrets Manager ARN of your secret key used for basic authentication with your Confluent
     *   schema registry.
     * - `CLIENT_CERTIFICATE_TLS_AUTH` – The Secrets Manager ARN of your secret key containing the certificate chain
     *   (X.509 PEM), private key (PKCS#8 PEM), and private key password (optional) used for mutual TLS authentication with
     *   your Confluent schema registry.
     * - `SERVER_ROOT_CA_CERTIFICATE` – The Secrets Manager ARN of your secret key containing the root CA certificate
     *   (X.509 PEM) used for TLS encryption with your Confluent schema registry.
     * - `OAUTHBEARER_AUTH` – The Secrets Manager ARN of your secret key containing the OAuth 2.0 credentials that Lambda
     *   uses to acquire an access token for your Confluent schema registry. For the contents of the secret, see Configuring
     *   the OAuth secret [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/kafka-cluster-auth.html#smaa-auth-oauth-secret
     *
     * @var KafkaSchemaRegistryAuthType::*|null
     */
    private $type;

    /**
     * The URI of the secret (Secrets Manager secret ARN) to authenticate with your schema registry.
     *
     * @var string|null
     */
    private $uri;

    /**
     * @param array{
     *   Type?: KafkaSchemaRegistryAuthType::*|null,
     *   URI?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? null;
        $this->uri = $input['URI'] ?? null;
    }

    /**
     * @param array{
     *   Type?: KafkaSchemaRegistryAuthType::*|null,
     *   URI?: string|null,
     * }|KafkaSchemaRegistryAccessConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return KafkaSchemaRegistryAuthType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }
}
