---
layout: client category: clients name: CodeCommit package: async-aws/code-commit
---

## Usage

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
