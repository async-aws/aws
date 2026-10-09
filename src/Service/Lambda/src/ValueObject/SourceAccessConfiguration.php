<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\SourceAccessType;

/**
 * To secure and define access to your event source, you can specify the authentication protocol, VPC components, or
 * virtual host.
 */
final class SourceAccessConfiguration
{
    /**
     * The type of authentication protocol, VPC components, or virtual host for your event source. For example:
     * `"Type":"SASL_SCRAM_512_AUTH"`.
     *
     * - `BASIC_AUTH` – (Amazon MQ) The Secrets Manager secret that stores your broker credentials.
     * - `BASIC_AUTH` – (Self-managed Apache Kafka) The Secrets Manager ARN of your secret key used for SASL/PLAIN
     *   authentication of your Apache Kafka brokers.
     * - `VPC_SUBNET` – (Self-managed Apache Kafka) The subnets associated with your VPC. Lambda connects to these subnets
     *   to fetch data from your self-managed Apache Kafka cluster.
     * - `VPC_SECURITY_GROUP` – (Self-managed Apache Kafka) The VPC security group used to manage access to your
     *   self-managed Apache Kafka brokers.
     * - `SASL_SCRAM_256_AUTH` – (Self-managed Apache Kafka) The Secrets Manager ARN of your secret key used for SASL
     *   SCRAM-256 authentication of your self-managed Apache Kafka brokers.
     * - `SASL_SCRAM_512_AUTH` – (Amazon MSK, Self-managed Apache Kafka) The Secrets Manager ARN of your secret key used
     *   for SASL SCRAM-512 authentication of your self-managed Apache Kafka brokers.
     * - `VIRTUAL_HOST` –- (RabbitMQ) The name of the virtual host in your RabbitMQ broker. Lambda uses this RabbitMQ host
     *   as the event source. This property cannot be specified in an UpdateEventSourceMapping API call.
     * - `CLIENT_CERTIFICATE_TLS_AUTH` – (Amazon MSK, self-managed Apache Kafka) The Secrets Manager ARN of your secret
     *   key containing the certificate chain (X.509 PEM), private key (PKCS#8 PEM), and private key password (optional)
     *   used for mutual TLS authentication of your MSK/Apache Kafka brokers.
     * - `SERVER_ROOT_CA_CERTIFICATE` – (Self-managed Apache Kafka) The Secrets Manager ARN of your secret key containing
     *   the root CA certificate (X.509 PEM) used for TLS encryption of your Apache Kafka brokers.
     * - `OAUTHBEARER_AUTH` – (Self-managed Apache Kafka) The Secrets Manager ARN of your secret key containing the OAuth
     *   2.0 credentials that Lambda uses for SASL/OAUTHBEARER authentication with your Apache Kafka brokers. For the
     *   contents of the secret, see Configuring the OAuth secret [^1].
     * - `OAUTHBEARER_SCOPE` – (Self-managed Apache Kafka) The OAuth 2.0 scope that Lambda requests when it acquires an
     *   access token. The `URI` field holds the scope value, not a secret ARN. This type requires `OAUTHBEARER_AUTH`.
     * - `OAUTHBEARER_AUDIENCE` – (Self-managed Apache Kafka) The OAuth 2.0 audience that Lambda requests when it acquires
     *   an access token. The `URI` field holds the audience value, not a secret ARN. This type requires either
     *   `OAUTHBEARER_AUTH` or `IAM_OAUTHBEARER_AUTH`.
     * - `OAUTHBEARER_LOGICAL_CLUSTER` – (Self-managed Apache Kafka) The logical cluster identifier that Lambda sends to a
     *   Confluent Cloud broker. The `URI` field holds the identifier, not a secret ARN. This type requires
     *   `OAUTHBEARER_AUTH`.
     * - `OAUTHBEARER_IDENTITY_POOL` – (Self-managed Apache Kafka) The identity pool identifier that Lambda sends to a
     *   Confluent Cloud broker. The `URI` field holds the identifier, not a secret ARN. This type requires
     *   `OAUTHBEARER_AUTH`.
     * - `IAM_AUTH` – (Self-managed Apache Kafka) Authenticate with Identity and Access Management (IAM). Your function's
     *   execution role signs each connection, so there is no secret to provide. Omit the `URI` field for this type.
     * - `IAM_OAUTHBEARER_AUTH` – (Self-managed Apache Kafka) Authenticate with an Amazon Web Services web identity token
     *   over SASL/OAUTHBEARER. Lambda requests the token for your function's execution role, so there is no secret to
     *   provide. Omit the `URI` field for this type. This type requires `OAUTHBEARER_AUDIENCE` and does not support the
     *   other `OAUTHBEARER` types.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/kafka-cluster-auth.html#smaa-auth-oauth-secret
     *
     * @var SourceAccessType::*|null
     */
    private $type;

    /**
     * The value for your chosen configuration in `Type`. For example: `"URI":
     * "arn:aws:secretsmanager:us-east-1:01234567890:secret:MyBrokerSecretName"`.
     *
     * @var string|null
     */
    private $uri;

    /**
     * @param array{
     *   Type?: SourceAccessType::*|null,
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
     *   Type?: SourceAccessType::*|null,
     *   URI?: string|null,
     * }|SourceAccessConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SourceAccessType::*|null
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
