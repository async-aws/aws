---
layout: client
category: clients
name: Comprehend
package: async-aws/comprehend
---

## Usage

### Detect Language

```php
use AsyncAws\Comprehend\ComprehendClient;
use AsyncAws\Comprehend\Input\DetectDominantLanguageRequest;

$comprehend = new ComprehendClient();

$result = $comprehend->detectDominantLanguage(new DetectDominantLanguageRequest([
    'Text' => 'Jag gillar glass'
]));

foreach ($result->getLanguages() as $language) {
    echo sprintf('%s: %s', $language->getLanguageCode(), $language->getScore()).PHP_EOL;
}

// Prints
// sv: 0.99807989597321
```
