<?php

namespace AsyncAws\Scheduler\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Scheduler\Enum\ActionAfterCompletion;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\Target;

final class CreateScheduleInput extends Input
{
    /**
     * Specifies the action that EventBridge Scheduler applies to the schedule after the schedule completes invoking the
     * target.
     *
     * @var ActionAfterCompletion::*|null
     */
    private $actionAfterCompletion;

    /**
     * Unique, case-sensitive identifier you provide to ensure the idempotency of the request. If you do not specify a
     * client token, EventBridge Scheduler uses a randomly generated token for the request to ensure idempotency.
     *
     * @var string|null
     */
    private $clientToken;

    /**
     * The description you specify for the schedule.
     *
     * @var string|null
     */
    private $description;

    /**
     * The date, in UTC, before which the schedule can invoke its target. Depending on the schedule's recurrence expression,
     * invocations might stop on, or before, the `EndDate` you specify. EventBridge Scheduler ignores `EndDate` for one-time
     * schedules.
     *
     * @var \DateTimeImmutable|null
     */
    private $endDate;

    /**
     * Allows you to configure a time window during which EventBridge Scheduler invokes the schedule.
     *
     * @required
     *
     * @var FlexibleTimeWindow|null
     */
    private $flexibleTimeWindow;

    /**
     * The name of the schedule group to associate with this schedule. If you omit this, the default schedule group is used.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The Amazon Resource Name (ARN) for the customer managed KMS key that EventBridge Scheduler will use to encrypt and
     * decrypt your data.
     *
     * @var string|null
     */
    private $kmsKeyArn;

    /**
     * The name of the schedule that you are creating.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * The expression that defines when the schedule runs. The following formats are supported.
     *
     * - `at` expression - `at(yyyy-mm-ddThh:mm:ss)`
     * - `rate` expression - `rate(value unit)`
     * - `cron` expression - `cron(fields)`
     *
     * You can use `at` expressions to create one-time schedules that invoke a target once, at the time and in the time
     * zone, that you specify. You can use `rate` and `cron` expressions to create recurring schedules. Rate-based schedules
     * are useful when you want to invoke a target at regular intervals, such as every 15 minutes or every five days.
     * Cron-based schedules are useful when you want to invoke a target periodically at a specific time, such as at 8:00 am
     * (UTC+0) every 1st day of the month.
     *
     * A `cron` expression consists of six fields separated by white spaces: `(minutes hours day_of_month month day_of_week
     * year)`.
     *
     * A `rate` expression consists of a *value* as a positive integer, and a *unit* with the following options: `minute` |
     * `minutes` | `hour` | `hours` | `day` | `days`
     *
     * For more information and examples, see Schedule types on EventBridge Scheduler [^1] in the *EventBridge Scheduler
     * User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/scheduler/latest/UserGuide/schedule-types.html
     *
     * @required
     *
     * @var string|null
     */
    private $scheduleExpression;

    /**
     * The timezone in which the scheduling expression is evaluated.
     *
     * @var string|null
     */
    private $scheduleExpressionTimezone;

    /**
     * The date, in UTC, after which the schedule can begin invoking its target. Depending on the schedule's recurrence
     * expression, invocations might occur on, or after, the `StartDate` you specify. EventBridge Scheduler ignores
     * `StartDate` for one-time schedules.
     *
     * @var \DateTimeImmutable|null
     */
    private $startDate;

    /**
     * Specifies whether the schedule is enabled or disabled.
     *
     * @var ScheduleState::*|null
     */
    private $state;

    /**
     * The schedule's target.
     *
     * @required
     *
     * @var Target|null
     */
    private $target;

    /**
     * @param array{
     *   ActionAfterCompletion?: ActionAfterCompletion::*|null,
     *   ClientToken?: string|null,
     *   Description?: string|null,
     *   EndDate?: \DateTimeImmutable|string|null,
     *   FlexibleTimeWindow?: FlexibleTimeWindow|array,
     *   GroupName?: string|null,
     *   KmsKeyArn?: string|null,
     *   Name?: string,
     *   ScheduleExpression?: string,
     *   ScheduleExpressionTimezone?: string|null,
     *   StartDate?: \DateTimeImmutable|string|null,
     *   State?: ScheduleState::*|null,
     *   Target?: Target|array,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->actionAfterCompletion = $input['ActionAfterCompletion'] ?? null;
        $this->clientToken = $input['ClientToken'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->endDate = !isset($input['EndDate']) ? null : ($input['EndDate'] instanceof \DateTimeImmutable ? $input['EndDate'] : new \DateTimeImmutable($input['EndDate']));
        $this->flexibleTimeWindow = isset($input['FlexibleTimeWindow']) ? FlexibleTimeWindow::create($input['FlexibleTimeWindow']) : null;
        $this->groupName = $input['GroupName'] ?? null;
        $this->kmsKeyArn = $input['KmsKeyArn'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->scheduleExpression = $input['ScheduleExpression'] ?? null;
        $this->scheduleExpressionTimezone = $input['ScheduleExpressionTimezone'] ?? null;
        $this->startDate = !isset($input['StartDate']) ? null : ($input['StartDate'] instanceof \DateTimeImmutable ? $input['StartDate'] : new \DateTimeImmutable($input['StartDate']));
        $this->state = $input['State'] ?? null;
        $this->target = isset($input['Target']) ? Target::create($input['Target']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ActionAfterCompletion?: ActionAfterCompletion::*|null,
     *   ClientToken?: string|null,
     *   Description?: string|null,
     *   EndDate?: \DateTimeImmutable|string|null,
     *   FlexibleTimeWindow?: FlexibleTimeWindow|array,
     *   GroupName?: string|null,
     *   KmsKeyArn?: string|null,
     *   Name?: string,
     *   ScheduleExpression?: string,
     *   ScheduleExpressionTimezone?: string|null,
     *   StartDate?: \DateTimeImmutable|string|null,
     *   State?: ScheduleState::*|null,
     *   Target?: Target|array,
     *   '@region'?: string|null,
     * }|CreateScheduleInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ActionAfterCompletion::*|null
     */
    public function getActionAfterCompletion(): ?string
    {
        return $this->actionAfterCompletion;
    }

    public function getClientToken(): ?string
    {
        return $this->clientToken;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getFlexibleTimeWindow(): ?FlexibleTimeWindow
    {
        return $this->flexibleTimeWindow;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getKmsKeyArn(): ?string
    {
        return $this->kmsKeyArn;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getScheduleExpression(): ?string
    {
        return $this->scheduleExpression;
    }

    public function getScheduleExpressionTimezone(): ?string
    {
        return $this->scheduleExpressionTimezone;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return ScheduleState::*|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function getTarget(): ?Target
    {
        return $this->target;
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
        if (null === $v = $this->name) {
            throw new InvalidArgument(\sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Name'] = $v;
        $uriString = '/schedules/' . rawurlencode($uri['Name']);

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param ActionAfterCompletion::*|null $value
     */
    public function setActionAfterCompletion(?string $value): self
    {
        $this->actionAfterCompletion = $value;

        return $this;
    }

    public function setClientToken(?string $value): self
    {
        $this->clientToken = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setEndDate(?\DateTimeImmutable $value): self
    {
        $this->endDate = $value;

        return $this;
    }

    public function setFlexibleTimeWindow(?FlexibleTimeWindow $value): self
    {
        $this->flexibleTimeWindow = $value;

        return $this;
    }

    public function setGroupName(?string $value): self
    {
        $this->groupName = $value;

        return $this;
    }

    public function setKmsKeyArn(?string $value): self
    {
        $this->kmsKeyArn = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setScheduleExpression(?string $value): self
    {
        $this->scheduleExpression = $value;

        return $this;
    }

    public function setScheduleExpressionTimezone(?string $value): self
    {
        $this->scheduleExpressionTimezone = $value;

        return $this;
    }

    public function setStartDate(?\DateTimeImmutable $value): self
    {
        $this->startDate = $value;

        return $this;
    }

    /**
     * @param ScheduleState::*|null $value
     */
    public function setState(?string $value): self
    {
        $this->state = $value;

        return $this;
    }

    public function setTarget(?Target $value): self
    {
        $this->target = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->actionAfterCompletion) {
            if (!ActionAfterCompletion::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ActionAfterCompletion" for "%s". The value "%s" is not a valid "ActionAfterCompletion".', __CLASS__, $v));
            }
            $payload['ActionAfterCompletion'] = $v;
        }
        if (null === $v = $this->clientToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['ClientToken'] = $v;
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null !== $v = $this->endDate) {
            $payload['EndDate'] = $v->getTimestamp();
        }
        if (null === $v = $this->flexibleTimeWindow) {
            throw new InvalidArgument(\sprintf('Missing parameter "FlexibleTimeWindow" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['FlexibleTimeWindow'] = $v->requestBody();
        if (null !== $v = $this->groupName) {
            $payload['GroupName'] = $v;
        }
        if (null !== $v = $this->kmsKeyArn) {
            $payload['KmsKeyArn'] = $v;
        }

        if (null === $v = $this->scheduleExpression) {
            throw new InvalidArgument(\sprintf('Missing parameter "ScheduleExpression" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ScheduleExpression'] = $v;
        if (null !== $v = $this->scheduleExpressionTimezone) {
            $payload['ScheduleExpressionTimezone'] = $v;
        }
        if (null !== $v = $this->startDate) {
            $payload['StartDate'] = $v->getTimestamp();
        }
        if (null !== $v = $this->state) {
            if (!ScheduleState::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "State" for "%s". The value "%s" is not a valid "ScheduleState".', __CLASS__, $v));
            }
            $payload['State'] = $v;
        }
        if (null === $v = $this->target) {
            throw new InvalidArgument(\sprintf('Missing parameter "Target" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Target'] = $v->requestBody();

        return $payload;
    }
}
