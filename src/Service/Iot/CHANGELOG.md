# Change Log

## NOT RELEASED

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: This release allows AWS IoT Core users to specify a TLS security policy when creating and updating AWS IoT Domain Configurations.
- AWS api-change: Support additional OTA states in GetOTAUpdate API
- AWS api-change: Re-release to remove unexpected API changes
- AWS api-change: A recurring maintenance window is an optional configuration used for rolling out the job document to all devices in the target group observing a predetermined start time, duration, and frequency that the maintenance window occurs.
- AWS api-change: Added support for IoT Rules Engine Cloudwatch Logs action batch mode.
- AWS api-change: Job scheduling enables the scheduled rollout of a Job with start and end times and a customizable end behavior when end time is reached. This is available for continuous and snapshot jobs. Added support for MQTT5 properties to AWS IoT TopicRule Republish Action.
- AWS api-change: This release add new api listRelatedResourcesForAuditFinding and new member type IssuerCertificates for Iot device device defender Audit.
- AWS api-change: This release adds the Amazon Location action to IoT Rules Engine.
- AWS api-change: The release is to support attach a provisioning template to CACert for JITP function, Customer now doesn't have to hardcode a roleArn and templateBody during register a CACert to enable JITP.
- AWS api-change: GA release the ability to enable/disable IoT Fleet Indexing for Device Defender and Named Shadow information, and search them through IoT Fleet Indexing APIs. This includes Named Shadow Selection as a part of the UpdateIndexingConfiguration API.
- AWS api-change: This release adds support to register a CA certificate without having to provide a verification certificate. This also allows multiple AWS accounts to register the same CA in the same region.
- AWS api-change: This release ease the restriction for the input of tag value to align with AWS standard, now instead of min length 1, we change it to min length 0.
- AWS enhancement: Documentation update for China region ListMetricValues for IoT
- AWS api-change: AWS IoT Jobs now allows you to create up to 100,000 active continuous and snapshot jobs by using concurrency control.
- AWS api-change: AWS IoT - AWS IoT Device Defender adds support to list metric datapoints collected for IoT devices through the ListMetricValues API
- AWS enhancement: Doc only update for IoT that fixes customer-reported issues.
- AWS api-change: This release adds support for configuring AWS IoT logging level per client ID, source IP, or principal ID.
- AWS api-change: This release adds an automatic retry mechanism for AWS IoT Jobs. You can now define a maximum number of retries for each Job rollout, along with the criteria to trigger the retry for FAILED/TIMED_OUT/ALL(both FAILED an TIMED_OUT) job.
- AWS api-change: This release allows customer to enable caching of custom authorizer on HTTP protocol for clients that use persistent or Keep-Alive connection in order to reduce the number of Lambda invocations.
- AWS api-change: Added the ability to enable/disable IoT Fleet Indexing for Device Defender and Named Shadow information, and search them through IoT Fleet Indexing APIs.
- AWS api-change: This release introduces a new feature, Managed Job Template, for AWS IoT Jobs Service. Customers can now use service provided managed job templates to easily create jobs for supported standard job actions.

## 0.1.0

First version
