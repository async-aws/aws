<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The waiter promise is always returned from every API call to a waiter.
 */
class Waiter
{
    public const STATE_SUCCESS = 'success';
    public const STATE_FAILURE = 'failure';
    public const STATE_PENDING = 'pending';

    protected const WAIT_TIMEOUT = 30.0;
    protected const WAIT_DELAY = 5.0;

    /**
     * @var AbstractApi|null
     */
    protected $awsClient;

    /**
     * Input used to build the API request that generate this Waiter.
     *
     * @var object|null
     */
    protected $input;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var string|null
     */
    private $finalState;

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient, AbstractApi $awsClient, $request)
    {
        $this->response = $response;
        $this->httpClient = $httpClient;
        $this->awsClient = $awsClient;
        $this->input = $request;
    }

    public function __destruct()
    {
        if (isset($this->response)) {
            $this->response->cancel();
        }
    }

    final public function isSuccess(): bool
    {
        return self::STATE_SUCCESS === $this->getState();
    }

    final public function isFailure(): bool
    {
        return self::STATE_FAILURE === $this->getState();
    }

    final public function isPending(): bool
    {
        return self::STATE_PENDING === $this->getState();
    }

    final public function getState(): string
    {
        if (null !== $this->finalState) {
            return $this->finalState;
        }

        if (!isset($this->response)) {
            $this->stealResponse($this->refreshState());
        }

        $exception = null;
        $this->resolve();

        $state = $this->extractState($this->response, $exception);
        unset($this->response);

        switch ($state) {
            case self::STATE_SUCCESS:
            case self::STATE_FAILURE:
                $this->finalState = $state;

                break;
            case self::STATE_PENDING:
                break;
            default:
                throw new \LogicException(sprintf('Unexpected state "%s" from Waiter "%s".', $state, __CLASS__));
        }

        return $state;
    }

    /**
     * Make sure the actual request is executed.
     *
     * @param float|null $timeout Duration in seconds before aborting. When null wait until the end of execution.
     *
     * @return bool whether the request is executed or not
     *
     * @throws NetworkException
     */
    final public function resolve(?float $timeout = null): bool
    {
        if (!isset($this->response)) {
            return true;
        }

        try {
            if (null !== $timeout && $this->httpClient->stream($this->response, $timeout)->current()->isTimeout()) {
                return false;
            }
        } catch (TransportExceptionInterface $e) {
            // When a network error occurs
            throw new NetworkException('Could not contact remote server.', 0, $e);
        }

        return true;
    }

    /**
     * Returns info on the current request.
     *
     * @return array{
     *                resolved: bool,
     *                response?: ?ResponseInterface,
     *                status?: int
     *                }
     */
    final public function info(): array
    {
        if (!isset($this->response)) {
            return [
                'resolved' => true,
            ];
        }

        return [
            'resolved' => false,
            'response' => $this->response,
            'status' => (int) $this->response->getInfo('http_code'),
        ];
    }

    final public function cancel(): void
    {
        if (!isset($this->response)) {
            return;
        }

        $this->response->cancel();
    }

    /**
     * Wait until the state is success.
     * Stopped when the state become Failure or the defined timeout is reached.
     *
     * @param float $timeout Duration in seconds before aborting
     * @param float $delay   Duration in seconds between each check
     */
    final public function wait(float $timeout = null, float $delay = null): bool
    {
        $timeout = $timeout ?? static::WAIT_TIMEOUT;
        $delay = $delay ?? static::WAIT_DELAY;

        $start = \microtime(true);
        while (true) {
            if (!$this->resolve($timeout - (\microtime(true) - $start))) {
                break;
            }

            switch ($this->getState()) {
                case self::STATE_SUCCESS:
                case self::STATE_FAILURE:
                    return true;
            }

            if ($delay > $timeout - (\microtime(true) - $start)) {
                break;
            }
            \usleep((int) ceil($delay * 1000000));
            $this->stealResponse($this->refreshState());
        }

        return false;
    }

    protected function extractState(ResponseInterface $response, ?HttpException $exception): string
    {
        return self::STATE_PENDING;
    }

    protected function refreshState(): Waiter
    {
        return $this;
    }

    private function stealResponse(self $waiter): void
    {
        $this->response = $waiter->response;
        unset($waiter->response);
    }
}
