---
layout: client
category: clients
name: CodeBuild
package: async-aws/code-build
---

## Usage

### Start a build

```php
use AsyncAws\CodeBuild\CodeBuildClient;
use AsyncAws\CodeBuild\Input\StartBuildInput;

$codeBuild = new CodeBuildClient();

$buildSpec = <<<EOS
version: 0.2

phases:
    build:
        commands:
            - whoami >stdout 2>stderr
artifacts:
    files:
        - stdout
        - stderr
EOS;

$codeBuild->startBuild(new StartBuildInput([
    'projectName' => 'my-project',
    'environmentVariablesOverride' => [
        [
            'name' => 'APP_ENV',
            'type' => 'PLAINTEXT',
            'value' => 'prod',
        ]
    ],
    'buildspecOverride' => $buildSpec,
    'timeoutInMinutesOverride' => 10,
]));
```

### Stop a build

```php
use AsyncAws\CodeBuild\CodeBuildClient;
use AsyncAws\CodeBuild\Input\StopBuildInput;

$codeBuild = new CodeBuildClient();

$response = $codeBuild->stopBuild(new StopBuildInput([
    'id' => 'build-identifier',
]));

echo $response->getBuild()->getArtifacts()->getLocation();
```
