---
layout: client
category: clients
name: KMS
package: async-aws/kms
---

## Usage


### Encrypt plaintext

```php
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Input\EncryptRequest;
use AsyncAws\Kms\KmsClient;

$kms = new KmsClient();

$output = $kms->encrypt(new EncryptRequest([
    'EncryptionAlgorithm' => EncryptionAlgorithmSpec::SYMMETRIC_DEFAULT,
    'KeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
    'Plaintext' => '{"message": "Hello, World!"}',
]));

// binary ciphertext string
$ciphertextBlob = $output->getCiphertextBlob();
```

### Decrypt ciphertext

```php
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Input\DecryptRequest;
use AsyncAws\Kms\KmsClient;

$kms = new KmsClient();

$output = $kms->decrypt(new DecryptRequest([
    'CiphertextBlob' => 'binary-ciphertext-string',
    'EncryptionAlgorithm' => EncryptionAlgorithmSpec::SYMMETRIC_DEFAULT,
    'KeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
]));

// binary plaintext string
$plaintextBlob = $output->getPlaintext();
```

### Generate data key

```php
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;
use AsyncAws\Kms\KmsClient;

$kms = new KmsClient();

$output = $kms->generateDataKey(new GenerateDataKeyRequest([
    'KeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
    'KeySpec' => DataKeySpec::AES_256,
]));


// binary ciphertext string
$ciphertextBlob = $output->getCiphertextBlob();

// binary plaintext string
$plaintextBlob = $output->getPlaintext();
```
