---
layout: client
category: clients
name: ElastiCache
package: async-aws/elasti-cache
---

## Usage

### List all clusters

```php
use AsyncAws\ElastiCache\ElastiCacheClient;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;

$elastiCache = new ElastiCacheClient();
$clusters = $elastiCache->describeCacheClusters();

foreach ($clusters as $cluster) {
    echo 'Cluster id: '.$cluster->getCacheClusterId().PHP_EOL;
}
```
