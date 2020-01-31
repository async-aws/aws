<?php

require __DIR__.'/vendor/autoload.php';

$s3 = new \AsyncAws\S3\S3Client(\Symfony\Component\HttpClient\HttpClient::create(), [
    'region' => 'us-east-1',
    'profile' => 'nyholm',
]);

$s3->putObjectAcl([
    'foo'=>'bar',
    'Key'=>'pas',
    'Bucket'=>'pas',
    'AccessControlPolicy' => [
        'Owner' => [
            'DisplayName' => 'Tobias',
            'ID' => '4711'
        ],
        'Grants' => [
            [
                'Grantee' => [
                    'Type' => 'Group',
                    'DisplayName' => 'Foo Group',
                ],
                'Permission' => 'FULL_CONTROL',
            ]
        ]
    ]

]);
exit(0);
$s3->putObject([
    'Bucket' => 'bref-example',
    'Key' => 'test.txt',
    'Body' => 'foobar',
    ''
]);