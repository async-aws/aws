---
layout: client
category: clients
name: Timestream Query
package: async-aws/timestream-query
---

## Usage

### Execute a query

```php
use AsyncAws\TimestreamQuery\Input\QueryInput;
use AsyncAws\TimestreamQuery\TimestreamQueryClient;

$timestreamQuery = new TimestreamQueryClient();

$result = $timestreamQuery->query(new QueryInput([
    'ClientToken' => 'qwertyuiop',
    'QueryString' => 'SELECT * FROM db.tbl ORDER BY time DESC LIMIT 10',
]));

$rows = $result->getRows();
```
