<?php

namespace AsyncAws\BedrockRuntime\Tests\Unit\Input;

use AsyncAws\BedrockRuntime\Input\ConverseRequest;
use AsyncAws\BedrockRuntime\ValueObject\AnyToolChoice;
use AsyncAws\BedrockRuntime\ValueObject\AutoToolChoice;
use AsyncAws\BedrockRuntime\ValueObject\ContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\Document;
use AsyncAws\BedrockRuntime\ValueObject\DocumentBlock;
use AsyncAws\BedrockRuntime\ValueObject\DocumentSource;
use AsyncAws\BedrockRuntime\ValueObject\GuardrailConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\GuardrailConverseContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\GuardrailConverseImageBlock;
use AsyncAws\BedrockRuntime\ValueObject\GuardrailConverseImageSource;
use AsyncAws\BedrockRuntime\ValueObject\GuardrailConverseTextBlock;
use AsyncAws\BedrockRuntime\ValueObject\ImageBlock;
use AsyncAws\BedrockRuntime\ValueObject\ImageSource;
use AsyncAws\BedrockRuntime\ValueObject\InferenceConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\Message;
use AsyncAws\BedrockRuntime\ValueObject\PerformanceConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\PromptVariableValues;
use AsyncAws\BedrockRuntime\ValueObject\ReasoningContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\ReasoningTextBlock;
use AsyncAws\BedrockRuntime\ValueObject\S3Location;
use AsyncAws\BedrockRuntime\ValueObject\SpecificToolChoice;
use AsyncAws\BedrockRuntime\ValueObject\SystemContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\Tool;
use AsyncAws\BedrockRuntime\ValueObject\ToolChoice;
use AsyncAws\BedrockRuntime\ValueObject\ToolConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\ToolInputSchema;
use AsyncAws\BedrockRuntime\ValueObject\ToolResultBlock;
use AsyncAws\BedrockRuntime\ValueObject\ToolResultContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\ToolSpecification;
use AsyncAws\BedrockRuntime\ValueObject\ToolUseBlock;
use AsyncAws\BedrockRuntime\ValueObject\VideoBlock;
use AsyncAws\BedrockRuntime\ValueObject\VideoSource;
use AsyncAws\Core\Test\TestCase;

class ConverseRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ConverseRequest([
            'modelId' => 'us.anthropic.claude-3-7-sonnet-20250219-v1:0',
            'messages' => [new Message([
                'role' => 'user',
                'content' => [new ContentBlock([
                    'text' => 'change me',
                    'image' => new ImageBlock([
                        'format' => 'png',
                        'source' => new ImageSource([
                            'bytes' => 'change me',
                        ]),
                    ]),
                    'document' => new DocumentBlock([
                        'format' => 'pdf',
                        'name' => 'change me',
                        'source' => new DocumentSource([
                            'bytes' => 'change me',
                        ]),
                    ]),
                    'video' => new VideoBlock([
                        'format' => 'mkv',
                        'source' => new VideoSource([
                            'bytes' => 'change me',
                            's3Location' => new S3Location([
                                'uri' => 'change me',
                                'bucketOwner' => 'change me',
                            ]),
                        ]),
                    ]),
                    'toolUse' => new ToolUseBlock([
                        'toolUseId' => 'change me',
                        'name' => 'change me',
                        'input' => new Document(),
                    ]),
                    'toolResult' => new ToolResultBlock([
                        'toolUseId' => 'change me',
                        'content' => [new ToolResultContentBlock([
                            'json' => new Document(),
                            'text' => 'change me',
                            'image' => new ImageBlock([
                                'format' => 'png',
                                'source' => new ImageSource([
                                    'bytes' => 'change me',
                                ]),
                            ]),
                            'document' => new DocumentBlock([
                                'format' => 'pdf',
                                'name' => 'change me',
                                'source' => new DocumentSource([
                                    'bytes' => 'change me',
                                ]),
                            ]),
                            'video' => new VideoBlock([
                                'format' => 'mkv',
                                'source' => new VideoSource([
                                    'bytes' => 'change me',
                                    's3Location' => new S3Location([
                                        'uri' => 'change me',
                                        'bucketOwner' => 'change me',
                                    ]),
                                ]),
                            ]),
                        ])],
                        'status' => 'success',
                    ]),
                    'guardContent' => new GuardrailConverseContentBlock([
                        'text' => new GuardrailConverseTextBlock([
                            'text' => 'change me',
                            'qualifiers' => ['grounding_source'],
                        ]),
                        'image' => new GuardrailConverseImageBlock([
                            'format' => 'png',
                            'source' => new GuardrailConverseImageSource([
                                'bytes' => 'change me',
                            ]),
                        ]),
                    ]),
                    'reasoningContent' => new ReasoningContentBlock([
                        'reasoningText' => new ReasoningTextBlock([
                            'text' => 'change me',
                            'signature' => 'change me',
                        ]),
                        'redactedContent' => 'change me',
                    ]),
                ])],
            ])],
            'system' => [new SystemContentBlock([
                'text' => 'change me',
                'guardContent' => new GuardrailConverseContentBlock([
                    'text' => new GuardrailConverseTextBlock([
                        'text' => 'change me',
                        'qualifiers' => ['grounding_source'],
                    ]),
                    'image' => new GuardrailConverseImageBlock([
                        'format' => 'png',
                        'source' => new GuardrailConverseImageSource([
                            'bytes' => 'change me',
                        ]),
                    ]),
                ]),
            ])],
            'inferenceConfig' => new InferenceConfiguration([
                'maxTokens' => 1337,
                'temperature' => 1337,
                'topP' => 1337,
                'stopSequences' => ['change me'],
            ]),
            'toolConfig' => new ToolConfiguration([
                'tools' => [new Tool([
                    'toolSpec' => new ToolSpecification([
                        'name' => 'change me',
                        'description' => 'change me',
                        'inputSchema' => new ToolInputSchema([
                            'json' => new Document(),
                        ]),
                    ]),
                ])],
                'toolChoice' => new ToolChoice([
                    'auto' => new AutoToolChoice(),
                    'any' => new AnyToolChoice(),
                    'tool' => new SpecificToolChoice([
                        'name' => 'change me',
                    ]),
                ]),
            ]),
            'guardrailConfig' => new GuardrailConfiguration([
                'guardrailIdentifier' => 'change me',
                'guardrailVersion' => 'change me',
                'trace' => 'enabled',
            ]),
            'additionalModelRequestFields' => new Document(),
            'promptVariables' => ['change me' => new PromptVariableValues([
                'text' => 'change me',
            ])],
            'additionalModelResponseFieldPaths' => ['change me'],
            'requestMetadata' => ['change me' => 'change me'],
            'performanceConfig' => new PerformanceConfiguration([
                'latency' => 'standard',
            ]),
        ]);

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_Converse.html
        $expected = '
            POST /model/us.anthropic.claude-3-7-sonnet-20250219-v1%3A0/converse HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {
   "messages":[
      {
         "role":"user",
         "content":[
            {
               "text":"change me",
               "image":{
                  "format":"png",
                  "source":{
                     "bytes":"Y2hhbmdlIG1l"
                  }
               },
               "document":{
                  "format":"pdf",
                  "name":"change me",
                  "source":{
                     "bytes":"Y2hhbmdlIG1l"
                  }
               },
               "video":{
                  "format":"mkv",
                  "source":{
                     "bytes":"Y2hhbmdlIG1l",
                     "s3Location":{
                        "uri":"change me",
                        "bucketOwner":"change me"
                     }
                  }
               },
               "toolUse":{
                  "toolUseId":"change me",
                  "name":"change me",
                  "input":[]
               },
               "toolResult":{
                  "toolUseId":"change me",
                  "content":[
                     {
                        "json":[],
                        "text":"change me",
                        "image":{
                           "format":"png",
                           "source":{
                              "bytes":"Y2hhbmdlIG1l"
                           }
                        },
                        "document":{
                           "format":"pdf",
                           "name":"change me",
                           "source":{
                              "bytes":"Y2hhbmdlIG1l"
                           }
                        },
                        "video":{
                           "format":"mkv",
                           "source":{
                              "bytes":"Y2hhbmdlIG1l",
                              "s3Location":{
                                 "uri":"change me",
                                 "bucketOwner":"change me"
                              }
                           }
                        }
                     }
                  ],
                  "status":"success"
               },
               "guardContent":{
                  "text":{
                     "text":"change me",
                     "qualifiers":[
                        "grounding_source"
                     ]
                  },
                  "image":{
                     "format":"png",
                     "source":{
                        "bytes":"Y2hhbmdlIG1l"
                     }
                  }
               },
               "reasoningContent":{
                  "reasoningText":{
                     "text":"change me",
                     "signature":"change me"
                  },
                  "redactedContent":"Y2hhbmdlIG1l"
               }
            }
         ]
      }
   ],
   "system":[
      {
         "text":"change me",
         "guardContent":{
            "text":{
               "text":"change me",
               "qualifiers":[
                  "grounding_source"
               ]
            },
            "image":{
               "format":"png",
               "source":{
                  "bytes":"Y2hhbmdlIG1l"
               }
            }
         }
      }
   ],
   "inferenceConfig":{
      "maxTokens":1337,
      "temperature":1337,
      "topP":1337,
      "stopSequences":[
         "change me"
      ]
   },
   "toolConfig":{
      "tools":[
         {
            "toolSpec":{
               "name":"change me",
               "description":"change me",
               "inputSchema":{
                  "json":[]
               }
            }
         }
      ],
      "toolChoice":{
         "auto":[],
         "any":[],
         "tool":{
            "name":"change me"
         }
      }
   },
   "guardrailConfig":{
      "guardrailIdentifier":"change me",
      "guardrailVersion":"change me",
      "trace":"enabled"
   },
   "additionalModelRequestFields":[],
   "promptVariables":{
      "change me":{
         "text":"change me"
      }
   },
   "additionalModelResponseFieldPaths":[
      "change me"
   ],
   "requestMetadata":{
      "change me":"change me"
   },
   "performanceConfig":{
      "latency":"standard"
   }
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
