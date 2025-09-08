<?php

namespace AsyncAws\EventBridge\ValueObject;

/**
 * Represents the results of an event submitted to an event bus.
 *
 * If the submission was successful, the entry has the event ID in it. Otherwise, you can use the error code and error
 * message to identify the problem with the entry.
 *
 * For information about the errors that are common to all actions, see Common Errors [^1].
 *
 * [^1]: https://docs.aws.amazon.com/eventbridge/latest/APIReference/CommonErrors.html
 */
final class PutEventsResultEntry
{
    /**
     * The ID of the event.
     *
     * @var string|null
     */
    private $eventId;

    /**
     * The error code that indicates why the event submission failed.
     *
     * Retryable errors include:
     *
     * - `InternalFailure [^1]`
     *
     *   The request processing has failed because of an unknown error, exception or failure.
     * - `ThrottlingException [^2]`
     *
     *   The request was denied due to request throttling.
     *
     * Non-retryable errors include:
     *
     * - `AccessDeniedException [^3]`
     *
     *   You do not have sufficient access to perform this action.
     * - `InvalidAccountIdException`
     *
     *   The account ID provided is not valid.
     * - `InvalidArgument`
     *
     *   A specified parameter is not valid.
     * - `MalformedDetail`
     *
     *   The JSON provided is not valid.
     * - `RedactionFailure`
     *
     *   Redacting the CloudTrail event failed.
     * - `NotAuthorizedForSourceException`
     *
     *   You do not have permissions to publish events with this source onto this event bus.
     * - `NotAuthorizedForDetailTypeException`
     *
     *   You do not have permissions to publish events with this detail type onto this event bus.
     *
     * [^1]: https://docs.aws.amazon.com/eventbridge/latest/APIReference/CommonErrors.html
     * [^2]: https://docs.aws.amazon.com/eventbridge/latest/APIReference/CommonErrors.html
     * [^3]: https://docs.aws.amazon.com/eventbridge/latest/APIReference/CommonErrors.html
     *
     * @var string|null
     */
    private $errorCode;

    /**
     * The error message that explains why the event submission failed.
     *
     * @var string|null
     */
    private $errorMessage;

    /**
     * @param array{
     *   EventId?: string|null,
     *   ErrorCode?: string|null,
     *   ErrorMessage?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->eventId = $input['EventId'] ?? null;
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->errorMessage = $input['ErrorMessage'] ?? null;
    }

    /**
     * @param array{
     *   EventId?: string|null,
     *   ErrorCode?: string|null,
     *   ErrorMessage?: string|null,
     * }|PutEventsResultEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getEventId(): ?string
    {
        return $this->eventId;
    }
}
