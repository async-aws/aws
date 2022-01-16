<?php

namespace AsyncAws\Kms;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Exception\DependencyTimeoutException;
use AsyncAws\Kms\Exception\DisabledException;
use AsyncAws\Kms\Exception\IncorrectKeyException;
use AsyncAws\Kms\Exception\InvalidCiphertextException;
use AsyncAws\Kms\Exception\InvalidGrantTokenException;
use AsyncAws\Kms\Exception\InvalidKeyUsageException;
use AsyncAws\Kms\Exception\KeyUnavailableException;
use AsyncAws\Kms\Exception\KMSInternalException;
use AsyncAws\Kms\Exception\KMSInvalidStateException;
use AsyncAws\Kms\Exception\NotFoundException;
use AsyncAws\Kms\Input\DecryptRequest;
use AsyncAws\Kms\Input\EncryptRequest;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;
use AsyncAws\Kms\Result\DecryptResponse;
use AsyncAws\Kms\Result\EncryptResponse;
use AsyncAws\Kms\Result\GenerateDataKeyResponse;

class KmsClient extends AbstractApi
{
    /**
     * Decrypts ciphertext that was encrypted by a KMS key using any of the following operations:.
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_Decrypt.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#decrypt
     *
     * @param array{
     *   CiphertextBlob: string,
     *   EncryptionContext?: array<string, string>,
     *   GrantTokens?: string[],
     *   KeyId?: string,
     *   EncryptionAlgorithm?: EncryptionAlgorithmSpec::*,
     *   @region?: string,
     * }|DecryptRequest $input
     *
     * @throws NotFoundException
     * @throws DisabledException
     * @throws InvalidCiphertextException
     * @throws KeyUnavailableException
     * @throws IncorrectKeyException
     * @throws InvalidKeyUsageException
     * @throws DependencyTimeoutException
     * @throws InvalidGrantTokenException
     * @throws KMSInternalException
     * @throws KMSInvalidStateException
     */
    public function decrypt($input): DecryptResponse
    {
        $input = DecryptRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Decrypt', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NotFoundException' => NotFoundException::class,
            'DisabledException' => DisabledException::class,
            'InvalidCiphertextException' => InvalidCiphertextException::class,
            'KeyUnavailableException' => KeyUnavailableException::class,
            'IncorrectKeyException' => IncorrectKeyException::class,
            'InvalidKeyUsageException' => InvalidKeyUsageException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidGrantTokenException' => InvalidGrantTokenException::class,
            'KMSInternalException' => KMSInternalException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
        ]]));

        return new DecryptResponse($response);
    }

    /**
     * Encrypts plaintext into ciphertext by using a KMS key. The `Encrypt` operation has two primary use cases:.
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_Encrypt.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#encrypt
     *
     * @param array{
     *   KeyId: string,
     *   Plaintext: string,
     *   EncryptionContext?: array<string, string>,
     *   GrantTokens?: string[],
     *   EncryptionAlgorithm?: EncryptionAlgorithmSpec::*,
     *   @region?: string,
     * }|EncryptRequest $input
     *
     * @throws NotFoundException
     * @throws DisabledException
     * @throws KeyUnavailableException
     * @throws DependencyTimeoutException
     * @throws InvalidKeyUsageException
     * @throws InvalidGrantTokenException
     * @throws KMSInternalException
     * @throws KMSInvalidStateException
     */
    public function encrypt($input): EncryptResponse
    {
        $input = EncryptRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Encrypt', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NotFoundException' => NotFoundException::class,
            'DisabledException' => DisabledException::class,
            'KeyUnavailableException' => KeyUnavailableException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidKeyUsageException' => InvalidKeyUsageException::class,
            'InvalidGrantTokenException' => InvalidGrantTokenException::class,
            'KMSInternalException' => KMSInternalException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
        ]]));

        return new EncryptResponse($response);
    }

    /**
     * Generates a unique symmetric data key for client-side encryption. This operation returns a plaintext copy of the data
     * key and a copy that is encrypted under a KMS key that you specify. You can use the plaintext key to encrypt your data
     * outside of KMS and store the encrypted data key with the encrypted data.
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_GenerateDataKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#generatedatakey
     *
     * @param array{
     *   KeyId: string,
     *   EncryptionContext?: array<string, string>,
     *   NumberOfBytes?: int,
     *   KeySpec?: DataKeySpec::*,
     *   GrantTokens?: string[],
     *   @region?: string,
     * }|GenerateDataKeyRequest $input
     *
     * @throws NotFoundException
     * @throws DisabledException
     * @throws KeyUnavailableException
     * @throws DependencyTimeoutException
     * @throws InvalidKeyUsageException
     * @throws InvalidGrantTokenException
     * @throws KMSInternalException
     * @throws KMSInvalidStateException
     */
    public function generateDataKey($input): GenerateDataKeyResponse
    {
        $input = GenerateDataKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GenerateDataKey', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NotFoundException' => NotFoundException::class,
            'DisabledException' => DisabledException::class,
            'KeyUnavailableException' => KeyUnavailableException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidKeyUsageException' => InvalidKeyUsageException::class,
            'InvalidGrantTokenException' => InvalidGrantTokenException::class,
            'KMSInternalException' => KMSInternalException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
        ]]));

        return new GenerateDataKeyResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://kms.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ProdFips':
                return [
                    'endpoint' => 'https://kms-fips.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'af-south-1':
                return [
                    'endpoint' => 'https://kms.af-south-1.amazonaws.com',
                    'signRegion' => 'af-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'af-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.af-south-1.amazonaws.com',
                    'signRegion' => 'af-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-east-1':
                return [
                    'endpoint' => 'https://kms.ap-east-1.amazonaws.com',
                    'signRegion' => 'ap-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-east-1.amazonaws.com',
                    'signRegion' => 'ap-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-1':
                return [
                    'endpoint' => 'https://kms.ap-northeast-1.amazonaws.com',
                    'signRegion' => 'ap-northeast-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-northeast-1.amazonaws.com',
                    'signRegion' => 'ap-northeast-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-2':
                return [
                    'endpoint' => 'https://kms.ap-northeast-2.amazonaws.com',
                    'signRegion' => 'ap-northeast-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-northeast-2.amazonaws.com',
                    'signRegion' => 'ap-northeast-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-3':
                return [
                    'endpoint' => 'https://kms.ap-northeast-3.amazonaws.com',
                    'signRegion' => 'ap-northeast-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-3-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-northeast-3.amazonaws.com',
                    'signRegion' => 'ap-northeast-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-south-1':
                return [
                    'endpoint' => 'https://kms.ap-south-1.amazonaws.com',
                    'signRegion' => 'ap-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-south-1.amazonaws.com',
                    'signRegion' => 'ap-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-1':
                return [
                    'endpoint' => 'https://kms.ap-southeast-1.amazonaws.com',
                    'signRegion' => 'ap-southeast-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-southeast-1.amazonaws.com',
                    'signRegion' => 'ap-southeast-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-2':
                return [
                    'endpoint' => 'https://kms.ap-southeast-2.amazonaws.com',
                    'signRegion' => 'ap-southeast-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-southeast-2.amazonaws.com',
                    'signRegion' => 'ap-southeast-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-3':
                return [
                    'endpoint' => 'https://kms.ap-southeast-3.amazonaws.com',
                    'signRegion' => 'ap-southeast-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-3-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-southeast-3.amazonaws.com',
                    'signRegion' => 'ap-southeast-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1':
                return [
                    'endpoint' => 'https://kms.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-central-1':
                return [
                    'endpoint' => 'https://kms.eu-central-1.amazonaws.com',
                    'signRegion' => 'eu-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-central-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-central-1.amazonaws.com',
                    'signRegion' => 'eu-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-north-1':
                return [
                    'endpoint' => 'https://kms.eu-north-1.amazonaws.com',
                    'signRegion' => 'eu-north-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-north-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-north-1.amazonaws.com',
                    'signRegion' => 'eu-north-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-south-1':
                return [
                    'endpoint' => 'https://kms.eu-south-1.amazonaws.com',
                    'signRegion' => 'eu-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-south-1.amazonaws.com',
                    'signRegion' => 'eu-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-1':
                return [
                    'endpoint' => 'https://kms.eu-west-1.amazonaws.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-west-1.amazonaws.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-2':
                return [
                    'endpoint' => 'https://kms.eu-west-2.amazonaws.com',
                    'signRegion' => 'eu-west-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-west-2.amazonaws.com',
                    'signRegion' => 'eu-west-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-3':
                return [
                    'endpoint' => 'https://kms.eu-west-3.amazonaws.com',
                    'signRegion' => 'eu-west-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-3-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-west-3.amazonaws.com',
                    'signRegion' => 'eu-west-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'me-south-1':
                return [
                    'endpoint' => 'https://kms.me-south-1.amazonaws.com',
                    'signRegion' => 'me-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'me-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.me-south-1.amazonaws.com',
                    'signRegion' => 'me-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'sa-east-1':
                return [
                    'endpoint' => 'https://kms.sa-east-1.amazonaws.com',
                    'signRegion' => 'sa-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'sa-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.sa-east-1.amazonaws.com',
                    'signRegion' => 'sa-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1':
                return [
                    'endpoint' => 'https://kms.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2':
                return [
                    'endpoint' => 'https://kms.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://kms.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://kms.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://kms.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-west-1':
                return [
                    'endpoint' => 'https://kms.us-iso-west-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-iso-west-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://kms.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1':
                return [
                    'endpoint' => 'https://kms.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2':
                return [
                    'endpoint' => 'https://kms.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Kms".', $region));
    }
}
