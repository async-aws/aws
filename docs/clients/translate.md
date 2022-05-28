---
layout: client
category: clients
name: Translate
package: async-aws/translate
---

## Usage

### Write records

```php
use AsyncAws\Translate\TranslateClient;
use AsyncAws\Translate\ValueObject\TranslationSettings;

$translate = new TranslateClient();

$result = $translate->translateText([
    'Text' => 'Jag gillar glass',
    'SourceLanguageCode' => 'sv', // 'auto'
    'TargetLanguageCode' => 'en',
    'Settings' => new TranslationSettings([
        'Formality' => 'INFORMAL', // FORMAL
        // 'Profanity' => 'MASK',
    ]),
]);

echo $result->getTranslatedText(); // "I like ice cream"
```
