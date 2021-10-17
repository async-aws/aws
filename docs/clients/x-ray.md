---
layout: client
category: clients
name: XRay
package: async-aws/x-ray
---

## Usage

### Put trace segments

```php
use AsyncAws\XRay\Input\PutTraceSegmentsInput;
use AsyncAws\XRay\XRayClient;

$xRay = new XRayClient();

$xRay->publish(new PutTraceSegmentsInput([
    'TraceSegmentDocuments' => [
        json_encode([
            'name' => 'service-foo',
            'id' => '1111111111111111',
            'trace_id' => '1-58406520-a006649127e371903a2de979',
            'start_time' => 1480615200.010,
            'end_time' => 1480615200.090,
        ]),
    ],
]));
```
