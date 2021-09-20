<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\ValueObject\FunctionConfiguration;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class UpdateFunctionResponse extends Result
{
    /**
     * The `Function` object.
     */
    private $functionConfiguration;

    public function getFunctionConfiguration(): ?FunctionConfiguration
    {
        $this->initialize();

        return $this->functionConfiguration;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->functionConfiguration = empty($data['functionConfiguration']) ? null : new FunctionConfiguration([
            'functionId' => isset($data['functionConfiguration']['functionId']) ? (string) $data['functionConfiguration']['functionId'] : null,
            'functionArn' => isset($data['functionConfiguration']['functionArn']) ? (string) $data['functionConfiguration']['functionArn'] : null,
            'name' => isset($data['functionConfiguration']['name']) ? (string) $data['functionConfiguration']['name'] : null,
            'description' => isset($data['functionConfiguration']['description']) ? (string) $data['functionConfiguration']['description'] : null,
            'dataSourceName' => isset($data['functionConfiguration']['dataSourceName']) ? (string) $data['functionConfiguration']['dataSourceName'] : null,
            'requestMappingTemplate' => isset($data['functionConfiguration']['requestMappingTemplate']) ? (string) $data['functionConfiguration']['requestMappingTemplate'] : null,
            'responseMappingTemplate' => isset($data['functionConfiguration']['responseMappingTemplate']) ? (string) $data['functionConfiguration']['responseMappingTemplate'] : null,
            'functionVersion' => isset($data['functionConfiguration']['functionVersion']) ? (string) $data['functionConfiguration']['functionVersion'] : null,
            'syncConfig' => empty($data['functionConfiguration']['syncConfig']) ? null : new SyncConfig([
                'conflictHandler' => isset($data['functionConfiguration']['syncConfig']['conflictHandler']) ? (string) $data['functionConfiguration']['syncConfig']['conflictHandler'] : null,
                'conflictDetection' => isset($data['functionConfiguration']['syncConfig']['conflictDetection']) ? (string) $data['functionConfiguration']['syncConfig']['conflictDetection'] : null,
                'lambdaConflictHandlerConfig' => empty($data['functionConfiguration']['syncConfig']['lambdaConflictHandlerConfig']) ? null : new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => isset($data['functionConfiguration']['syncConfig']['lambdaConflictHandlerConfig']['lambdaConflictHandlerArn']) ? (string) $data['functionConfiguration']['syncConfig']['lambdaConflictHandlerConfig']['lambdaConflictHandlerArn'] : null,
                ]),
            ]),
        ]);
    }
}
