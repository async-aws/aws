<?php

namespace AsyncAws\BedrockRuntime\Tests\Unit\Result;

use AsyncAws\BedrockRuntime\Result\ConverseResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ConverseResponseTest extends TestCase
{
    public function testConverseResponse(): void
    {
        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_Converse.html
        $response = new SimpleMockedResponse('{
   "metrics":{
      "latencyMs":100
   },
   "output":{
      "message":{
         "content":[
            {
               "role":"user",
               "content":{
                  "text":"Hello world!"
               }
            }
         ]
      }
   },
   "performanceConfig":{
      "latency":"standard"
   },
   "stopReason":"end_turn",
   "trace":{
      "guardrail":{
         "inputAssessment":{
            "string":{
               "contentPolicy":{
                  "filters":[
                     {
                        "action":"string",
                        "confidence":"string",
                        "filterStrength":"string",
                        "type":"string"
                     }
                  ]
               },
               "contextualGroundingPolicy":{
                  "filters":[
                     {
                        "action":"string",
                        "score":1,
                        "threshold":1,
                        "type":"string"
                     }
                  ]
               },
               "invocationMetrics":{
                  "guardrailCoverage":{
                     "images":{
                        "guarded":1000,
                        "total":1000
                     },
                     "textCharacters":{
                        "guarded":1000,
                        "total":1000
                     }
                  },
                  "guardrailProcessingLatency":1000,
                  "usage":{
                     "contentPolicyUnits":1000,
                     "contextualGroundingPolicyUnits":1000,
                     "sensitiveInformationPolicyFreeUnits":1000,
                     "sensitiveInformationPolicyUnits":1000,
                     "topicPolicyUnits":1000,
                     "wordPolicyUnits":1000
                  }
               },
               "sensitiveInformationPolicy":{
                  "piiEntities":[
                     {
                        "action":"string",
                        "match":"string",
                        "type":"string"
                     }
                  ],
                  "regexes":[
                     {
                        "action":"string",
                        "match":"string",
                        "name":"string",
                        "regex":"string"
                     }
                  ]
               },
               "topicPolicy":{
                  "topics":[
                     {
                        "action":"string",
                        "name":"string",
                        "type":"string"
                     }
                  ]
               },
               "wordPolicy":{
                  "customWords":[
                     {
                        "action":"string",
                        "match":"string"
                     }
                  ],
                  "managedWordLists":[
                     {
                        "action":"string",
                        "match":"string",
                        "type":"string"
                     }
                  ]
               }
            }
         },
         "modelOutput":[
            "string"
         ],
         "outputAssessments":{
            "string":[
               {
                  "contentPolicy":{
                     "filters":[
                        {
                           "action":"string",
                           "confidence":"string",
                           "filterStrength":"string",
                           "type":"string"
                        }
                     ]
                  },
                  "contextualGroundingPolicy":{
                     "filters":[
                        {
                           "action":"string",
                           "score":1000,
                           "threshold":1000,
                           "type":"string"
                        }
                     ]
                  },
                  "invocationMetrics":{
                     "guardrailCoverage":{
                        "images":{
                           "guarded":1000,
                           "total":1000
                        },
                        "textCharacters":{
                           "guarded":1000,
                           "total":1000
                        }
                     },
                     "guardrailProcessingLatency":1000,
                     "usage":{
                        "contentPolicyUnits":1000,
                        "contextualGroundingPolicyUnits":1000,
                        "sensitiveInformationPolicyFreeUnits":1000,
                        "sensitiveInformationPolicyUnits":1000,
                        "topicPolicyUnits":1000,
                        "wordPolicyUnits":1000
                     }
                  },
                  "sensitiveInformationPolicy":{
                     "piiEntities":[
                        {
                           "action":"string",
                           "match":"string",
                           "type":"string"
                        }
                     ],
                     "regexes":[
                        {
                           "action":"string",
                           "match":"string",
                           "name":"string",
                           "regex":"string"
                        }
                     ]
                  },
                  "topicPolicy":{
                     "topics":[
                        {
                           "action":"string",
                           "name":"string",
                           "type":"string"
                        }
                     ]
                  },
                  "wordPolicy":{
                     "customWords":[
                        {
                           "action":"string",
                           "match":"string"
                        }
                     ],
                     "managedWordLists":[
                        {
                           "action":"string",
                           "match":"string",
                           "type":"string"
                        }
                     ]
                  }
               }
            ]
         }
      },
      "promptRouter":{
         "invokedModelId":"string"
      }
   },
   "usage":{
      "inputTokens":1000,
      "outputTokens":2000,
      "totalTokens":3000
   }
}');

        $client = new MockHttpClient($response);
        $result = new ConverseResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(100, $result->getMetrics()->getLatencyMs());
        self::assertSame('user', $result->getOutput()->getMessage()->getRole());
        self::assertSame('Hello world!', $result->getOutput()->getMessage()->getContent()[0]->getText());
        self::assertSame('end_turn', $result->getStopReason());
        self::assertSame('standard', $result->getPerformanceConfig()->getLatency());
        self::assertSame(1000, $result->getUsage()->getInputTokens());
        self::assertSame(2000, $result->getUsage()->getOutputTokens());
        self::assertSame(3000, $result->getUsage()->getTotalTokens());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getContentPolicy()->getFilters()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getContentPolicy()->getFilters()[0]->getConfidence());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getContentPolicy()->getFilters()[0]->getFilterStrength());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getContentPolicy()->getFilters()[0]->getType());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getContextualGroundingPolicy()->getFilters()[0]->getAction());
        self::assertSame(1, $result->getTrace()->getGuardrail()->getInputAssessment()['number']->getContextualGroundingPolicy()->getFilters()[0]->getScore());
        self::assertSame(1, $result->getTrace()->getGuardrail()->getInputAssessment()['number']->getContextualGroundingPolicy()->getFilters()[0]->getThreshold());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getContextualGroundingPolicy()->getFilters()[0]->getType());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getGuardrailCoverage()->getImages()->getGuarded());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getGuardrailCoverage()->getImages()->getTotal());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getGuardrailCoverage()->getTextCharacters()->getGuarded());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getGuardrailCoverage()->getTextCharacters()->getTotal());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getGuardrailProcessingLatency());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getUsage()->getContentPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getUsage()->getContextualGroundingPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getUsage()->getSensitiveInformationPolicyFreeUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getUsage()->getSensitiveInformationPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getUsage()->getTopicPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getInvocationMetrics()->getUsage()->getWordPolicyUnits());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getSensitiveInformationPolicy()->getPiiEntities()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getSensitiveInformationPolicy()->getPiiEntities()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getSensitiveInformationPolicy()->getPiiEntities()[0]->getType());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getSensitiveInformationPolicy()->getRegexes()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getSensitiveInformationPolicy()->getRegexes()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getSensitiveInformationPolicy()->getRegexes()[0]->getName());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getSensitiveInformationPolicy()->getRegexes()[0]->getRegex());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getTopicPolicy()->getTopics()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getTopicPolicy()->getTopics()[0]->getName());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getTopicPolicy()->getTopics()[0]->getType());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getWordPolicy()->getCustomWords()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getWordPolicy()->getCustomWords()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getWordPolicy()->getManagedWordLists()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getWordPolicy()->getManagedWordLists()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getInputAssessment()['string']->getWordPolicy()->getManagedWordLists()[0]->getType());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getModelOutput()[0]);
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContentPolicy()->getFilters()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContentPolicy()->getFilters()[0]->getConfidence());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContentPolicy()->getFilters()[0]->getFilterStrength());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContentPolicy()->getFilters()[0]->getType());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContextualGroundingPolicy()->getFilters()[0]->getAction());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContextualGroundingPolicy()->getFilters()[0]->getScore());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContextualGroundingPolicy()->getFilters()[0]->getThreshold());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getContextualGroundingPolicy()->getFilters()[0]->getType());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getGuardrailCoverage()->getImages()->getGuarded());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getGuardrailCoverage()->getImages()->getTotal());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getGuardrailCoverage()->getTextCharacters()->getGuarded());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getGuardrailCoverage()->getTextCharacters()->getTotal());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getGuardrailProcessingLatency());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getUsage()->getContentPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getUsage()->getContextualGroundingPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getUsage()->getSensitiveInformationPolicyFreeUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getUsage()->getSensitiveInformationPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getUsage()->getTopicPolicyUnits());
        self::assertSame(1000, $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getInvocationMetrics()->getUsage()->getWordPolicyUnits());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getSensitiveInformationPolicy()->getPiiEntities()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getSensitiveInformationPolicy()->getPiiEntities()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getSensitiveInformationPolicy()->getPiiEntities()[0]->getType());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getSensitiveInformationPolicy()->getRegexes()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getSensitiveInformationPolicy()->getRegexes()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getSensitiveInformationPolicy()->getRegexes()[0]->getName());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getSensitiveInformationPolicy()->getRegexes()[0]->getRegex());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getTopicPolicy()->getTopics()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getTopicPolicy()->getTopics()[0]->getName());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getTopicPolicy()->getTopics()[0]->getType());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getWordPolicy()->getCustomWords()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getWordPolicy()->getCustomWords()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getWordPolicy()->getManagedWordLists()[0]->getAction());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getWordPolicy()->getManagedWordLists()[0]->getMatch());
        self::assertSame('string', $result->getTrace()->getGuardrail()->getOutputAssessments()['string'][0]->getWordPolicy()->getManagedWordLists()[0]->getType());
        self::assertSame('string', $result->getTrace()->getPromptRouter()->getInvokedModelId());
    }
}
