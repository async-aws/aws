---
layout: client
category: clients
name: CodeCommit
package: async-aws/code-commit
---

## Usage

### List repositories

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\ListRepositoriesInput;

$codeCommit = new CodeCommitClient();

$repoList = $codeCommit->listRepositories(new ListRepositoriesInput([
    'order' => 'ascending', // can also be 'descending'
    'sortBy' => 'repositoryName', // can also be 'lastModifiedDate'
    'nextToken' => 'TOKEN-FROM-PREVIOUS-RESPONSE-IF-PAGINATING-THROUGH-RESULTS'
]));

$repos = $repoList->getRepositories();

// see example response at https://docs.aws.amazon.com/codecommit/latest/APIReference/API_ListRepositories.html
// for full list of information returned in $repos->getRepositories()
```

### Create a repository

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\CreateRepositoryInput;

$codeCommit = new CodeCommitClient();

$repo = $codeCommit->createRepository(new CreateRepositoryInput([
    'repositoryName' => 'my-repository-name', // name must be unique within your account
    'repositoryDescription' => 'this can be null if you like',
    'tags' => [
        'team' => 'platform-eng',
        'guild' => 'frontend',
        'any-other-tag' => 'whatever-you-want'
    ]
]));

$repo->getRepositoryMetadata();

// see example response at https://docs.aws.amazon.com/codecommit/latest/APIReference/API_CreateRepository.html
// for full list of information returned in $repo->getRepositoryMetadata()
```

### Delete a repository

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\DeleteRepositoryInput;

$codeCommit = new CodeCommitClient();

$repo = $codeCommit->deleteRepository(new DeleteRepositoryInput([
    'repositoryName' => 'name-of-repo-to-delete',
    ]
]));

var_dump($repo->getRepositoryId()); // returned id of the deleted repo
```

### Get a branch

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetBranchInput;

$codeCommit = new CodeCommitClient();

$branch = $codeCommit->getBranch(new GetBranchInput([
    'repositoryName' => $repoName,
    'branchName' => 'main'
]));

$latestCommitId = $branch->getBranch()->getCommitId();
```

### Get list of different blobs between two commits

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;

$codeCommit = new CodeCommitClient();

$differences = $codeCommit->getDifferences(new GetDifferencesInput([
    'repositoryName' => $repoName,
    'afterCommitSpecifier' => $latestCommitId,
    'beforeCommitSpecifier' => $baseCommitId
]));

foreach($differences->getDifferences() as $diff) {
    echo $diff->getAfterBlob()?->getPath(); // eg 'composer.json'
    echo $diff->getAfterBlob()?->getBlobId();
    echo $diff->getChangeType(); // eg 'A', 'M', 'D' for 'Added', 'Modified', 'Deleted'
}
```

### Get information about a blob

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetBlobInput;

$codeCommit = new CodeCommitClient();

$blob = $codeCommit->getBlob(new GetBlobInput([
    'repositoryName' => $repoName,
    'blobId' => $blobId
]));

echo $blob->getContent(); // Lorem ipsum dolor sit amet...
```

### Get information about a commit

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetCommitInput;

$codeCommit = new CodeCommitClient();

$commit = $codeCommit->getCommit(new GetCommitInput([
    'repositoryName' => $repoName,
    'commitId' => 'b58c341f3d493f7fc0b6b95a648a2e2397d0692f' # must be the full SHA hash of the commit
]));

echo $commit->getCommit()->getMessage(); // "Initial commit"

// see example response at https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetCommit.html
// for full list of information returned in $commit->getCommit()
```


### Update repository triggers

```php
use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\PutRepositoryTriggersInput;

$codeCommit = new CodeCommitClient();

$result = $codeCommit->putRepositoryTriggers(new PutRepositoryTriggersInput([
            'repositoryName' => 'async-aws-monorepo',
            'triggers' => [new RepositoryTrigger([
                'name' => 'NotifyOfCodeChanges',
                # ARN of your Lambda function which is going to get executed when something happens
                'destinationArn' => 'arn:aws:lambda:eu-west-1:123456789012:function:my-function',
                'customData' => 'any additional data you want for the execution context - maybe an id of some sort?',
                'branches' => ['main', 'development'], # send a blank array to be triggered on *all* branches
                'events' => ['createReference', 'deleteReference', 'updateReference'], # 'all' is also a valid input
            ])],
        ]));

echo $result->getConfigurationId(); // '6fa51cd8-35c1-EXAMPLE'
```
