<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\ValueObject\CapacityProviderConfig;
use AsyncAws\Lambda\ValueObject\DeadLetterConfig;
use AsyncAws\Lambda\ValueObject\DurableConfig;
use AsyncAws\Lambda\ValueObject\Environment;
use AsyncAws\Lambda\ValueObject\EphemeralStorage;
use AsyncAws\Lambda\ValueObject\FileSystemConfig;
use AsyncAws\Lambda\ValueObject\ImageConfig;
use AsyncAws\Lambda\ValueObject\LoggingConfig;
use AsyncAws\Lambda\ValueObject\SnapStart;
use AsyncAws\Lambda\ValueObject\TracingConfig;
use AsyncAws\Lambda\ValueObject\VpcConfig;

final class UpdateFunctionConfigurationRequest extends Input
{
    /**
     * The name or ARN of the Lambda function.
     *
     * **Name formats**
     *
     * - **Function name** – `my-function`.
     * - **Function ARN** – `arn:aws:lambda:us-west-2:123456789012:function:my-function`.
     * - **Partial ARN** – `123456789012:function:my-function`.
     *
     * The length constraint applies only to the full ARN. If you specify only the function name, it is limited to 64
     * characters in length.
     *
     * @required
     *
     * @var string|null
     */
    private $functionName;

    /**
     * The Amazon Resource Name (ARN) of the function's execution role.
     *
     * @var string|null
     */
    private $role;

    /**
     * The name of the method within your code that Lambda calls to run your function. Handler is required if the deployment
     * package is a .zip file archive. The format includes the file name. It can also include namespaces and other
     * qualifiers, depending on the runtime. For more information, see Lambda programming model [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/foundation-progmodel.html
     *
     * @var string|null
     */
    private $handler;

    /**
     * A description of the function.
     *
     * @var string|null
     */
    private $description;

    /**
     * The amount of time (in seconds) that Lambda allows a function to run before stopping it. The default is 3 seconds.
     * The maximum allowed value is 900 seconds. For more information, see Lambda execution environment [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/runtimes-context.html
     *
     * @var int|null
     */
    private $timeout;

    /**
     * The amount of memory available to the function [^1] at runtime. Increasing the function memory also increases its CPU
     * allocation. The default value is 128 MB. The value can be any multiple of 1 MB.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-function-common.html#configuration-memory-console
     *
     * @var int|null
     */
    private $memorySize;

    /**
     * For network connectivity to Amazon Web Services resources in a VPC, specify a list of security groups and subnets in
     * the VPC. When you connect a function to a VPC, it can access resources and the internet only through that VPC. For
     * more information, see Configuring a Lambda function to access resources in a VPC [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-vpc.html
     *
     * @var VpcConfig|null
     */
    private $vpcConfig;

    /**
     * Environment variables that are accessible from function code during execution.
     *
     * @var Environment|null
     */
    private $environment;

    /**
     * The identifier of the function's runtime [^1]. Runtime is required if the deployment package is a .zip file archive.
     * Specifying a runtime results in an error if you're deploying a function using a container image.
     *
     * The following list includes deprecated runtimes. Lambda blocks creating new functions and updating existing functions
     * shortly after each runtime is deprecated. For more information, see Runtime use after deprecation [^2].
     *
     * For a list of all currently supported runtimes, see Supported runtimes [^3].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html#runtime-deprecation-levels
     * [^3]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html#runtimes-supported
     *
     * @var Runtime::*|null
     */
    private $runtime;

    /**
     * A dead-letter queue configuration that specifies the queue or topic where Lambda sends asynchronous events when they
     * fail processing. For more information, see Dead-letter queues [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async.html#invocation-dlq
     *
     * @var DeadLetterConfig|null
     */
    private $deadLetterConfig;

    /**
     * The ARN of the Key Management Service (KMS) customer managed key that's used to encrypt the following resources:
     *
     * - The function's environment variables [^1].
     * - The function's Lambda SnapStart [^2] snapshots.
     * - When used with `SourceKMSKeyArn`, the unzipped version of the .zip deployment package that's used for function
     *   invocations. For more information, see Specifying a customer managed key for Lambda [^3].
     * - The optimized version of the container image that's used for function invocations. Note that this is not the same
     *   key that's used to protect your container image in the Amazon Elastic Container Registry (Amazon ECR). For more
     *   information, see Function lifecycle [^4].
     *
     * If you don't provide a customer managed key, Lambda uses an Amazon Web Services owned key [^5] or an Amazon Web
     * Services managed key [^6].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-envvars.html#configuration-envvars-encryption
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/snapstart-security.html
     * [^3]: https://docs.aws.amazon.com/lambda/latest/dg/encrypt-zip-package.html#enable-zip-custom-encryption
     * [^4]: https://docs.aws.amazon.com/lambda/latest/dg/images-create.html#images-lifecycle
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#aws-owned-cmk
     * [^6]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#aws-managed-cmk
     *
     * @var string|null
     */
    private $kmsKeyArn;

    /**
     * Set `Mode` to `Active` to sample and trace a subset of incoming requests with X-Ray [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/services-xray.html
     *
     * @var TracingConfig|null
     */
    private $tracingConfig;

    /**
     * Update the function only if the revision ID matches the ID that's specified. Use this option to avoid modifying a
     * function that has changed since you last read it.
     *
     * @var string|null
     */
    private $revisionId;

    /**
     * A list of function layers [^1] to add to the function's execution environment. Specify each layer by its ARN,
     * including the version.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     *
     * @var string[]|null
     */
    private $layers;

    /**
     * Connection settings for an Amazon EFS file system.
     *
     * @var FileSystemConfig[]|null
     */
    private $fileSystemConfigs;

    /**
     * Container image configuration values [^1] that override the values in the container image Docker file.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/images-create.html#images-parms
     *
     * @var ImageConfig|null
     */
    private $imageConfig;

    /**
     * The size of the function's `/tmp` directory in MB. The default value is 512, but can be any whole number between 512
     * and 10,240 MB. For more information, see Configuring ephemeral storage (console) [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-function-common.html#configuration-ephemeral-storage
     *
     * @var EphemeralStorage|null
     */
    private $ephemeralStorage;

    /**
     * The function's SnapStart [^1] setting.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/snapstart.html
     *
     * @var SnapStart|null
     */
    private $snapStart;

    /**
     * The function's Amazon CloudWatch Logs configuration settings.
     *
     * @var LoggingConfig|null
     */
    private $loggingConfig;

    /**
     * Configuration for the capacity provider that manages compute resources for Lambda functions.
     *
     * @var CapacityProviderConfig|null
     */
    private $capacityProviderConfig;

    /**
     * Configuration settings for durable functions. Allows updating execution timeout and retention period for functions
     * with durability enabled.
     *
     * @var DurableConfig|null
     */
    private $durableConfig;

    /**
     * @param array{
     *   FunctionName?: string,
     *   Role?: string|null,
     *   Handler?: string|null,
     *   Description?: string|null,
     *   Timeout?: int|null,
     *   MemorySize?: int|null,
     *   VpcConfig?: VpcConfig|array|null,
     *   Environment?: Environment|array|null,
     *   Runtime?: Runtime::*|null,
     *   DeadLetterConfig?: DeadLetterConfig|array|null,
     *   KMSKeyArn?: string|null,
     *   TracingConfig?: TracingConfig|array|null,
     *   RevisionId?: string|null,
     *   Layers?: string[]|null,
     *   FileSystemConfigs?: array<FileSystemConfig|array>|null,
     *   ImageConfig?: ImageConfig|array|null,
     *   EphemeralStorage?: EphemeralStorage|array|null,
     *   SnapStart?: SnapStart|array|null,
     *   LoggingConfig?: LoggingConfig|array|null,
     *   CapacityProviderConfig?: CapacityProviderConfig|array|null,
     *   DurableConfig?: DurableConfig|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->functionName = $input['FunctionName'] ?? null;
        $this->role = $input['Role'] ?? null;
        $this->handler = $input['Handler'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->timeout = $input['Timeout'] ?? null;
        $this->memorySize = $input['MemorySize'] ?? null;
        $this->vpcConfig = isset($input['VpcConfig']) ? VpcConfig::create($input['VpcConfig']) : null;
        $this->environment = isset($input['Environment']) ? Environment::create($input['Environment']) : null;
        $this->runtime = $input['Runtime'] ?? null;
        $this->deadLetterConfig = isset($input['DeadLetterConfig']) ? DeadLetterConfig::create($input['DeadLetterConfig']) : null;
        $this->kmsKeyArn = $input['KMSKeyArn'] ?? null;
        $this->tracingConfig = isset($input['TracingConfig']) ? TracingConfig::create($input['TracingConfig']) : null;
        $this->revisionId = $input['RevisionId'] ?? null;
        $this->layers = $input['Layers'] ?? null;
        $this->fileSystemConfigs = isset($input['FileSystemConfigs']) ? array_map([FileSystemConfig::class, 'create'], $input['FileSystemConfigs']) : null;
        $this->imageConfig = isset($input['ImageConfig']) ? ImageConfig::create($input['ImageConfig']) : null;
        $this->ephemeralStorage = isset($input['EphemeralStorage']) ? EphemeralStorage::create($input['EphemeralStorage']) : null;
        $this->snapStart = isset($input['SnapStart']) ? SnapStart::create($input['SnapStart']) : null;
        $this->loggingConfig = isset($input['LoggingConfig']) ? LoggingConfig::create($input['LoggingConfig']) : null;
        $this->capacityProviderConfig = isset($input['CapacityProviderConfig']) ? CapacityProviderConfig::create($input['CapacityProviderConfig']) : null;
        $this->durableConfig = isset($input['DurableConfig']) ? DurableConfig::create($input['DurableConfig']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   FunctionName?: string,
     *   Role?: string|null,
     *   Handler?: string|null,
     *   Description?: string|null,
     *   Timeout?: int|null,
     *   MemorySize?: int|null,
     *   VpcConfig?: VpcConfig|array|null,
     *   Environment?: Environment|array|null,
     *   Runtime?: Runtime::*|null,
     *   DeadLetterConfig?: DeadLetterConfig|array|null,
     *   KMSKeyArn?: string|null,
     *   TracingConfig?: TracingConfig|array|null,
     *   RevisionId?: string|null,
     *   Layers?: string[]|null,
     *   FileSystemConfigs?: array<FileSystemConfig|array>|null,
     *   ImageConfig?: ImageConfig|array|null,
     *   EphemeralStorage?: EphemeralStorage|array|null,
     *   SnapStart?: SnapStart|array|null,
     *   LoggingConfig?: LoggingConfig|array|null,
     *   CapacityProviderConfig?: CapacityProviderConfig|array|null,
     *   DurableConfig?: DurableConfig|array|null,
     *   '@region'?: string|null,
     * }|UpdateFunctionConfigurationRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCapacityProviderConfig(): ?CapacityProviderConfig
    {
        return $this->capacityProviderConfig;
    }

    public function getDeadLetterConfig(): ?DeadLetterConfig
    {
        return $this->deadLetterConfig;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDurableConfig(): ?DurableConfig
    {
        return $this->durableConfig;
    }

    public function getEnvironment(): ?Environment
    {
        return $this->environment;
    }

    public function getEphemeralStorage(): ?EphemeralStorage
    {
        return $this->ephemeralStorage;
    }

    /**
     * @return FileSystemConfig[]
     */
    public function getFileSystemConfigs(): array
    {
        return $this->fileSystemConfigs ?? [];
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function getHandler(): ?string
    {
        return $this->handler;
    }

    public function getImageConfig(): ?ImageConfig
    {
        return $this->imageConfig;
    }

    public function getKmsKeyArn(): ?string
    {
        return $this->kmsKeyArn;
    }

    /**
     * @return string[]
     */
    public function getLayers(): array
    {
        return $this->layers ?? [];
    }

    public function getLoggingConfig(): ?LoggingConfig
    {
        return $this->loggingConfig;
    }

    public function getMemorySize(): ?int
    {
        return $this->memorySize;
    }

    public function getRevisionId(): ?string
    {
        return $this->revisionId;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @return Runtime::*|null
     */
    public function getRuntime(): ?string
    {
        return $this->runtime;
    }

    public function getSnapStart(): ?SnapStart
    {
        return $this->snapStart;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function getTracingConfig(): ?TracingConfig
    {
        return $this->tracingConfig;
    }

    public function getVpcConfig(): ?VpcConfig
    {
        return $this->vpcConfig;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->functionName) {
            throw new InvalidArgument(\sprintf('Missing parameter "FunctionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['FunctionName'] = $v;
        $uriString = '/2015-03-31/functions/' . rawurlencode($uri['FunctionName']) . '/configuration';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCapacityProviderConfig(?CapacityProviderConfig $value): self
    {
        $this->capacityProviderConfig = $value;

        return $this;
    }

    public function setDeadLetterConfig(?DeadLetterConfig $value): self
    {
        $this->deadLetterConfig = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setDurableConfig(?DurableConfig $value): self
    {
        $this->durableConfig = $value;

        return $this;
    }

    public function setEnvironment(?Environment $value): self
    {
        $this->environment = $value;

        return $this;
    }

    public function setEphemeralStorage(?EphemeralStorage $value): self
    {
        $this->ephemeralStorage = $value;

        return $this;
    }

    /**
     * @param FileSystemConfig[] $value
     */
    public function setFileSystemConfigs(array $value): self
    {
        $this->fileSystemConfigs = $value;

        return $this;
    }

    public function setFunctionName(?string $value): self
    {
        $this->functionName = $value;

        return $this;
    }

    public function setHandler(?string $value): self
    {
        $this->handler = $value;

        return $this;
    }

    public function setImageConfig(?ImageConfig $value): self
    {
        $this->imageConfig = $value;

        return $this;
    }

    public function setKmsKeyArn(?string $value): self
    {
        $this->kmsKeyArn = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setLayers(array $value): self
    {
        $this->layers = $value;

        return $this;
    }

    public function setLoggingConfig(?LoggingConfig $value): self
    {
        $this->loggingConfig = $value;

        return $this;
    }

    public function setMemorySize(?int $value): self
    {
        $this->memorySize = $value;

        return $this;
    }

    public function setRevisionId(?string $value): self
    {
        $this->revisionId = $value;

        return $this;
    }

    public function setRole(?string $value): self
    {
        $this->role = $value;

        return $this;
    }

    /**
     * @param Runtime::*|null $value
     */
    public function setRuntime(?string $value): self
    {
        $this->runtime = $value;

        return $this;
    }

    public function setSnapStart(?SnapStart $value): self
    {
        $this->snapStart = $value;

        return $this;
    }

    public function setTimeout(?int $value): self
    {
        $this->timeout = $value;

        return $this;
    }

    public function setTracingConfig(?TracingConfig $value): self
    {
        $this->tracingConfig = $value;

        return $this;
    }

    public function setVpcConfig(?VpcConfig $value): self
    {
        $this->vpcConfig = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->role) {
            $payload['Role'] = $v;
        }
        if (null !== $v = $this->handler) {
            $payload['Handler'] = $v;
        }
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null !== $v = $this->timeout) {
            $payload['Timeout'] = $v;
        }
        if (null !== $v = $this->memorySize) {
            $payload['MemorySize'] = $v;
        }
        if (null !== $v = $this->vpcConfig) {
            $payload['VpcConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->environment) {
            $payload['Environment'] = $v->requestBody();
        }
        if (null !== $v = $this->runtime) {
            if (!Runtime::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Runtime" for "%s". The value "%s" is not a valid "Runtime".', __CLASS__, $v));
            }
            $payload['Runtime'] = $v;
        }
        if (null !== $v = $this->deadLetterConfig) {
            $payload['DeadLetterConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->kmsKeyArn) {
            $payload['KMSKeyArn'] = $v;
        }
        if (null !== $v = $this->tracingConfig) {
            $payload['TracingConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->revisionId) {
            $payload['RevisionId'] = $v;
        }
        if (null !== $v = $this->layers) {
            $index = -1;
            $payload['Layers'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Layers'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->fileSystemConfigs) {
            $index = -1;
            $payload['FileSystemConfigs'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['FileSystemConfigs'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->imageConfig) {
            $payload['ImageConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->ephemeralStorage) {
            $payload['EphemeralStorage'] = $v->requestBody();
        }
        if (null !== $v = $this->snapStart) {
            $payload['SnapStart'] = $v->requestBody();
        }
        if (null !== $v = $this->loggingConfig) {
            $payload['LoggingConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->capacityProviderConfig) {
            $payload['CapacityProviderConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->durableConfig) {
            $payload['DurableConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
