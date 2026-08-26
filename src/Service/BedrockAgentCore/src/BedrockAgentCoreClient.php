<?php

namespace AsyncAws\BedrockAgentCore;

use AsyncAws\BedrockAgentCore\Exception\AccessDeniedException;
use AsyncAws\BedrockAgentCore\Exception\InternalServerException;
use AsyncAws\BedrockAgentCore\Exception\ResourceNotFoundException;
use AsyncAws\BedrockAgentCore\Exception\RetryableConflictException;
use AsyncAws\BedrockAgentCore\Exception\RuntimeClientErrorException;
use AsyncAws\BedrockAgentCore\Exception\ServiceQuotaExceededException;
use AsyncAws\BedrockAgentCore\Exception\ThrottlingException;
use AsyncAws\BedrockAgentCore\Exception\ValidationException;
use AsyncAws\BedrockAgentCore\Input\InvokeAgentRuntimeRequest;
use AsyncAws\BedrockAgentCore\Result\InvokeAgentRuntimeResponse;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class BedrockAgentCoreClient extends AbstractApi
{
    /**
     * Sends a request to an agent or tool hosted in an Amazon Bedrock AgentCore Runtime and receives responses in
     * real-time.
     *
     * To invoke an agent, you can specify either the AgentCore Runtime ARN or the agent ID with an account ID, and provide
     * a payload containing your request. When you use the agent ID instead of the full ARN, you don't need to URL-encode
     * the identifier. You can optionally specify a qualifier to target a specific endpoint of the agent.
     *
     * This operation supports streaming responses, allowing you to receive partial responses as they become available. We
     * recommend using pagination to ensure that the operation returns quickly and successfully when processing large
     * responses.
     *
     * For example code, see Invoke an AgentCore Runtime agent [^1].
     *
     * If you're integrating your agent with OAuth, you can't use the Amazon Web Services SDK to call `InvokeAgentRuntime`.
     * Instead, make a HTTPS request to `InvokeAgentRuntime`. For an example, see Authenticate and authorize with Inbound
     * Auth and Outbound Auth [^2].
     *
     * To use this operation, you must have the `bedrock-agentcore:InvokeAgentRuntime` permission. If you are making a call
     * to `InvokeAgentRuntime` on behalf of a user ID with the `X-Amzn-Bedrock-AgentCore-Runtime-User-Id` header, You
     * require permissions to both actions (`bedrock-agentcore:InvokeAgentRuntime` and
     * `bedrock-agentcore:InvokeAgentRuntimeForUser`).
     *
     * [^1]: https://docs.aws.amazon.com/bedrock-agentcore/latest/devguide/runtime-invoke-agent.html
     * [^2]: https://docs.aws.amazon.com/bedrock-agentcore/latest/devguide/runtime-oauth.html
     *
     * @see https://docs.aws.amazon.com/bedrock-agentcore/latest/APIReference/API_InvokeAgentRuntime.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-bedrock-agentcore-2024-02-28.html#invokeagentruntime
     *
     * @param array{
     *   contentType?: string|null,
     *   accept?: string|null,
     *   mcpSessionId?: string|null,
     *   runtimeSessionId?: string|null,
     *   mcpProtocolVersion?: string|null,
     *   mcpMethod?: string|null,
     *   mcpName?: string|null,
     *   runtimeUserId?: string|null,
     *   traceId?: string|null,
     *   traceParent?: string|null,
     *   traceState?: string|null,
     *   baggage?: string|null,
     *   agentRuntimeArn: string,
     *   qualifier?: string|null,
     *   accountId?: string|null,
     *   payload: string,
     *   '@region'?: string|null,
     * }|InvokeAgentRuntimeRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ResourceNotFoundException
     * @throws RetryableConflictException
     * @throws RuntimeClientErrorException
     * @throws ServiceQuotaExceededException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function invokeAgentRuntime($input): InvokeAgentRuntimeResponse
    {
        $input = InvokeAgentRuntimeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'InvokeAgentRuntime', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'RetryableConflictException' => RetryableConflictException::class,
            'RuntimeClientError' => RuntimeClientErrorException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new InvokeAgentRuntimeResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        return [
            'endpoint' => "https://bedrock-agentcore.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'bedrock-agentcore',
            'signVersions' => ['v4'],
        ];
    }
}
