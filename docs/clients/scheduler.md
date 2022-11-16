---
layout: client
category: clients
name: Scheduler
package: async-aws/scheduler
---

## Usage

### Create a schedule group

```php
use AsyncAws\Scheduler\Input\CreateScheduleGroupInput;
use AsyncAws\Scheduler\SchedulerClient;

$scheduler = new SchedulerClient();

$result = $scheduler->createScheduleGroup(new CreateScheduleGroupInput([
    'ClientToken' => '644xadJgXECQEDF43qeAVLdlTaB0vSFSLXEraOws',
    'Name' => 'notifications',
]));

echo $result->getScheduleGroupArn();
```

### Delete a schedule group

```php
use AsyncAws\Scheduler\Input\DeleteScheduleGroupInput;
use AsyncAws\Scheduler\SchedulerClient;

$scheduler = new SchedulerClient();

$scheduler->deleteScheduleGroup(new DeleteScheduleGroupInput([
    'ClientToken' => 'HmaMY4Ut8zoKuqdPiku30yK3PwjBkq3YR6zzbQup',
    'Name' => 'notifications',
]));
```

### Get a schedule group

```php
use AsyncAws\Scheduler\Input\GetScheduleGroupInput;
use AsyncAws\Scheduler\SchedulerClient;

$scheduler = new SchedulerClient();

$result = $scheduler->getScheduleGroup(new GetScheduleGroupInput([
    'Name' => 'notifications',
]));

echo $result->getScheduleGroupArn();
```

### List the schedule groups

```php
use AsyncAws\Scheduler\Input\ListScheduleGroupsInput;
use AsyncAws\Scheduler\SchedulerClient;

$scheduler = new SchedulerClient();

$result = $scheduler->listScheduleGroups(new ListScheduleGroupsInput([
    'MaxResults' => 20,
    'NamePrefix' => 'notifications',
]));

foreach ($result->getScheduleGroups() as $scheduleGroup) {
    echo $scheduleGroup->getScheduleGroupArn()."\n";
}
```

### Create a schedule

```php
use AsyncAws\Scheduler\Enum\FlexibleTimeWindowMode;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Input\CreateScheduleInput;
use AsyncAws\Scheduler\SchedulerClient;
use AsyncAws\Scheduler\ValueObject\DeadLetterConfig;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\RetryPolicy;
use AsyncAws\Scheduler\ValueObject\Target;

$scheduler = new SchedulerClient();

$result = $scheduler->createSchedule(new CreateScheduleInput([
    'ClientToken' => 'pSNTPVxQFyaXWrSE5kmXlCNrr8dm2dvuAfGwjunx',
    'Description' => 'Send notification to Dave',
    'FlexibleTimeWindow' => new FlexibleTimeWindow([
        'Mode' => FlexibleTimeWindowMode::OFF,
    ]),
    'GroupName' => 'notifications',
    'Name' => 'dave1',
    'ScheduleExpression' => 'at(2023-01-01T00:00:00)',
    'ScheduleExpressionTimezone' => 'UTC',
    'State' => ScheduleState::ENABLED,
    'Target' => new Target([
        'Arn' => 'arn:aws:sqs:us-east-1:111111111111:notifications',
        'DeadLetterConfig' => new DeadLetterConfig([
            'Arn' => 'arn:aws:sqs:us-east-1:111111111111:dlq',
        ]),
        'Input' => '{"message": "Hi, Dave"}',
        'RetryPolicy' => new RetryPolicy([
            'MaximumEventAgeInSeconds' => 86400,
            'MaximumRetryAttempts' => 185,
        ]),
        'RoleArn' => 'arn:aws:iam::111111111111:role/scheduler-sqs-role',
    ]),
]));

echo $result->getScheduleArn();
```

### Update a schedule

```php
use AsyncAws\Scheduler\Enum\FlexibleTimeWindowMode;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Input\UpdateScheduleInput;
use AsyncAws\Scheduler\SchedulerClient;
use AsyncAws\Scheduler\ValueObject\DeadLetterConfig;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\RetryPolicy;
use AsyncAws\Scheduler\ValueObject\Target;

$scheduler = new SchedulerClient();

$result = $scheduler->updateSchedule(new UpdateScheduleInput([
    'ClientToken' => 'bsbC8XcvwK30nhaHOUPYhTgd0pVEhMBFBzGXmYbM',
    'Description' => 'Send notification to Dave',
    'FlexibleTimeWindow' => new FlexibleTimeWindow([
        'Mode' => FlexibleTimeWindowMode::OFF,
    ]),
    'GroupName' => 'notifications',
    'Name' => 'dave1',
    'ScheduleExpression' => 'at(2023-02-01T00:00:00)',
    'ScheduleExpressionTimezone' => 'UTC',
    'State' => ScheduleState::ENABLED,
    'Target' => new Target([
        'Arn' => 'arn:aws:sqs:us-east-1:111111111111:notifications',
        'DeadLetterConfig' => new DeadLetterConfig([
            'Arn' => 'arn:aws:sqs:us-east-1:111111111111:dlq',
        ]),
        'Input' => '{"message": "Hello, Dave"}',
        'RetryPolicy' => new RetryPolicy([
            'MaximumEventAgeInSeconds' => 86400,
            'MaximumRetryAttempts' => 185,
        ]),
        'RoleArn' => 'arn:aws:iam::111111111111:role/scheduler-sqs-role',
    ]),
]));

echo $result->getScheduleArn();
```

### Delete a schedule

```php
use AsyncAws\Scheduler\Input\DeleteScheduleInput;
use AsyncAws\Scheduler\SchedulerClient;

$scheduler = new SchedulerClient();

$result = $scheduler->deleteSchedule(new DeleteScheduleInput([
    'ClientToken' => 'iRiOT59WAXAQDGxUkPyiEaoLFhNRVNf0qU9AapP6',
    'GroupName' => 'notifications',
    'Name' => 'dave1',
]));
```

### Get a schedule

```php
use AsyncAws\Scheduler\Input\GetScheduleInput;
use AsyncAws\Scheduler\SchedulerClient;

$scheduler = new SchedulerClient();

$result = $scheduler->getSchedule(new GetScheduleInput([
    'GroupName' => 'notifications',
    'Name' => 'dave1',
]));

echo $result->getScheduleArn();
```

### List the schedules

```php
use AsyncAws\Scheduler\Input\ListSchedulesInput;
use AsyncAws\Scheduler\SchedulerClient;

$scheduler = new SchedulerClient();

$result = $scheduler->listSchedules(new ListSchedulesInput([
    'GroupName' => 'notifications',
    'MaxResults' => 20,
    'NamePrefix' => 'dave',
    'State' => ScheduleState::ENABLED,
]));

foreach ($result->getSchedules() as $schedule) {
    echo $schedule->getScheduleArn()."\n";
}
```
