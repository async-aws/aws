<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * *Alias resource record sets only:* Information about the Amazon Web Services resource, such as a CloudFront
 * distribution or an Amazon S3 bucket, that you want to route traffic to.
 *
 * When creating resource record sets for a private hosted zone, note the following:
 *
 * - For information about creating failover resource record sets in a private hosted zone, see Configuring Failover in
 *   a Private Hosted Zone [^1].
 *
 * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-private-hosted-zones.html
 */
final class AliasTarget
{
    /**
     * *Alias resource records sets only*: The value used depends on where you want to route traffic:
     *
     * - `Amazon API Gateway custom regional APIs and edge-optimized APIs`:
     *
     *   Specify the hosted zone ID for your API. You can get the applicable value using the CLI command get-domain-names
     *   [^1]:
     *
     *   - For regional APIs, specify the value of `regionalHostedZoneId`.
     *   - For edge-optimized APIs, specify the value of `distributionHostedZoneId`.
     *
     * - `Amazon Virtual Private Cloud interface VPC endpoint`:
     *
     *   Specify the hosted zone ID for your interface endpoint. You can get the value of `HostedZoneId` using the CLI
     *   command describe-vpc-endpoints [^2].
     * - `CloudFront distribution`:
     *
     *   Specify `Z2FDTNDATAQYW2`.
     *
     *   > Alias resource record sets for CloudFront can't be created in a private zone.
     *
     * - `Elastic Beanstalk environment`:
     *
     *   Specify the hosted zone ID for the region that you created the environment in. The environment must have a
     *   regionalized subdomain. For a list of regions and the corresponding hosted zone IDs, see Elastic Beanstalk
     *   endpoints and quotas [^3] in the *Amazon Web Services General Reference*.
     * - `ELB load balancer`:
     *
     *   Specify the value of the hosted zone ID for the load balancer. Use the following methods to get the hosted zone ID:
     *
     *   - Elastic Load Balancing endpoints and quotas [^4] topic in the *Amazon Web Services General Reference*: Use the
     *     value that corresponds with the region that you created your load balancer in. Note that there are separate
     *     columns for Application and Classic Load Balancers and for Network Load Balancers.
     *   - **Amazon Web Services Management Console**: Go to the Amazon EC2 page, choose **Load Balancers** in the
     *     navigation pane, select the load balancer, and get the value of the **Hosted zone** field on the **Description**
     *     tab.
     *   - **Elastic Load Balancing API**: Use `DescribeLoadBalancers` to get the applicable value. For more information,
     *     see the applicable guide:
     *
     *     - Classic Load Balancers: Use DescribeLoadBalancers [^5] to get the value of `CanonicalHostedZoneNameId`.
     *     - Application and Network Load Balancers: Use DescribeLoadBalancers [^6] to get the value of
     *       `CanonicalHostedZoneId`.
     *
     *   - **CLI**: Use `describe-load-balancers` to get the applicable value. For more information, see the applicable
     *     guide:
     *
     *     - Classic Load Balancers: Use describe-load-balancers [^7] to get the value of `CanonicalHostedZoneNameId`.
     *     - Application and Network Load Balancers: Use describe-load-balancers [^8] to get the value of
     *       `CanonicalHostedZoneId`.
     *
     *
     * - `Global Accelerator accelerator`:
     *
     *   Specify `Z2BJ6XQ5FK7U4H`.
     * - `An Amazon S3 bucket configured as a static website`:
     *
     *   Specify the hosted zone ID for the region that you created the bucket in. For more information about valid values,
     *   see the table Amazon S3 Website Endpoints [^9] in the *Amazon Web Services General Reference*.
     * - `Another Route 53 resource record set in your hosted zone`:
     *
     *   Specify the hosted zone ID of your hosted zone. (An alias resource record set can't reference a resource record set
     *   in a different hosted zone.)
     *
     * [^1]: https://docs.aws.amazon.com/cli/latest/reference/apigateway/get-domain-names.html
     * [^2]: https://docs.aws.amazon.com/cli/latest/reference/ec2/describe-vpc-endpoints.html
     * [^3]: https://docs.aws.amazon.com/general/latest/gr/elasticbeanstalk.html
     * [^4]: https://docs.aws.amazon.com/general/latest/gr/elb.html
     * [^5]: https://docs.aws.amazon.com/elasticloadbalancing/2012-06-01/APIReference/API_DescribeLoadBalancers.html
     * [^6]: https://docs.aws.amazon.com/elasticloadbalancing/latest/APIReference/API_DescribeLoadBalancers.html
     * [^7]: http://docs.aws.amazon.com/cli/latest/reference/elb/describe-load-balancers.html
     * [^8]: http://docs.aws.amazon.com/cli/latest/reference/elbv2/describe-load-balancers.html
     * [^9]: https://docs.aws.amazon.com/general/latest/gr/s3.html#s3_website_region_endpoints
     *
     * @var string
     */
    private $hostedZoneId;

    /**
     * *Alias resource record sets only:* The value that you specify depends on where you want to route queries:
     *
     * - `Amazon API Gateway custom regional APIs and edge-optimized APIs`:
     *
     *   Specify the applicable domain name for your API. You can get the applicable value using the CLI command
     *   get-domain-names [^1]:
     *
     *   - For regional APIs, specify the value of `regionalDomainName`.
     *   - For edge-optimized APIs, specify the value of `distributionDomainName`. This is the name of the associated
     *     CloudFront distribution, such as `da1b2c3d4e5.cloudfront.net`.
     *
     *   > The name of the record that you're creating must match a custom domain name for your API, such as
     *   > `api.example.com`.
     *
     * - `Amazon Virtual Private Cloud interface VPC endpoint`:
     *
     *   Enter the API endpoint for the interface endpoint, such as
     *   `vpce-123456789abcdef01-example-us-east-1a.elasticloadbalancing.us-east-1.vpce.amazonaws.com`. For edge-optimized
     *   APIs, this is the domain name for the corresponding CloudFront distribution. You can get the value of `DnsName`
     *   using the CLI command describe-vpc-endpoints [^2].
     * - `CloudFront distribution`:
     *
     *   Specify the domain name that CloudFront assigned when you created your distribution.
     *
     *   Your CloudFront distribution must include an alternate domain name that matches the name of the resource record
     *   set. For example, if the name of the resource record set is *acme.example.com*, your CloudFront distribution must
     *   include *acme.example.com* as one of the alternate domain names. For more information, see Using Alternate Domain
     *   Names (CNAMEs) [^3] in the *Amazon CloudFront Developer Guide*.
     *
     *   You can't create a resource record set in a private hosted zone to route traffic to a CloudFront distribution.
     *
     *   > For failover alias records, you can't specify a CloudFront distribution for both the primary and secondary
     *   > records. A distribution must include an alternate domain name that matches the name of the record. However, the
     *   > primary and secondary records have the same name, and you can't include the same alternate domain name in more
     *   > than one distribution.
     *
     * - `Elastic Beanstalk environment`:
     *
     *   If the domain name for your Elastic Beanstalk environment includes the region that you deployed the environment in,
     *   you can create an alias record that routes traffic to the environment. For example, the domain name
     *   `my-environment.*us-west-2*.elasticbeanstalk.com` is a regionalized domain name.
     *
     *   ! For environments that were created before early 2016, the domain name doesn't include the region. To route
     *   ! traffic to these environments, you must create a CNAME record instead of an alias record. Note that you can't
     *   ! create a CNAME record for the root domain name. For example, if your domain name is example.com, you can create a
     *   ! record that routes traffic for acme.example.com to your Elastic Beanstalk environment, but you can't create a
     *   ! record that routes traffic for example.com to your Elastic Beanstalk environment.
     *
     *   For Elastic Beanstalk environments that have regionalized subdomains, specify the `CNAME` attribute for the
     *   environment. You can use the following methods to get the value of the CNAME attribute:
     *
     *   - *Amazon Web Services Management Console*: For information about how to get the value by using the console, see
     *     Using Custom Domains with Elastic Beanstalk [^4] in the *Elastic Beanstalk Developer Guide*.
     *   - *Elastic Beanstalk API*: Use the `DescribeEnvironments` action to get the value of the `CNAME` attribute. For
     *     more information, see DescribeEnvironments [^5] in the *Elastic Beanstalk API Reference*.
     *   - *CLI*: Use the `describe-environments` command to get the value of the `CNAME` attribute. For more information,
     *     see describe-environments [^6] in the *CLI Command Reference*.
     *
     * - `ELB load balancer`:
     *
     *   Specify the DNS name that is associated with the load balancer. Get the DNS name by using the Amazon Web Services
     *   Management Console, the ELB API, or the CLI.
     *
     *   - **Amazon Web Services Management Console**: Go to the EC2 page, choose **Load Balancers** in the navigation pane,
     *     choose the load balancer, choose the **Description** tab, and get the value of the **DNS name** field.
     *
     *     If you're routing traffic to a Classic Load Balancer, get the value that begins with **dualstack**. If you're
     *     routing traffic to another type of load balancer, get the value that applies to the record type, A or AAAA.
     *   - **Elastic Load Balancing API**: Use `DescribeLoadBalancers` to get the value of `DNSName`. For more information,
     *     see the applicable guide:
     *
     *     - Classic Load Balancers: DescribeLoadBalancers [^7]
     *     - Application and Network Load Balancers: DescribeLoadBalancers [^8]
     *
     *   - **CLI**: Use `describe-load-balancers` to get the value of `DNSName`. For more information, see the applicable
     *     guide:
     *
     *     - Classic Load Balancers: describe-load-balancers [^9]
     *     - Application and Network Load Balancers: describe-load-balancers [^10]
     *
     *
     * - `Global Accelerator accelerator`:
     *
     *   Specify the DNS name for your accelerator:
     *
     *   - **Global Accelerator API:** To get the DNS name, use DescribeAccelerator [^11].
     *   - **CLI:** To get the DNS name, use describe-accelerator [^12].
     *
     * - `Amazon S3 bucket that is configured as a static website`:
     *
     *   Specify the domain name of the Amazon S3 website endpoint that you created the bucket in, for example,
     *   `s3-website.us-east-2.amazonaws.com`. For more information about valid values, see the table Amazon S3 Website
     *   Endpoints [^13] in the *Amazon Web Services General Reference*. For more information about using S3 buckets for
     *   websites, see Getting Started with Amazon Route 53 [^14] in the *Amazon Route 53 Developer Guide.*
     * - `Another Route 53 resource record set`:
     *
     *   Specify the value of the `Name` element for a resource record set in the current hosted zone.
     *
     *   > If you're creating an alias record that has the same name as the hosted zone (known as the zone apex), you can't
     *   > specify the domain name for a record for which the value of `Type` is `CNAME`. This is because the alias record
     *   > must have the same type as the record that you're routing traffic to, and creating a CNAME record for the zone
     *   > apex isn't supported even for an alias record.
     *
     * [^1]: https://docs.aws.amazon.com/cli/latest/reference/apigateway/get-domain-names.html
     * [^2]: https://docs.aws.amazon.com/cli/latest/reference/ec2/describe-vpc-endpoints.html
     * [^3]: https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/CNAMEs.html
     * [^4]: https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/customdomains.html
     * [^5]: https://docs.aws.amazon.com/elasticbeanstalk/latest/api/API_DescribeEnvironments.html
     * [^6]: https://docs.aws.amazon.com/cli/latest/reference/elasticbeanstalk/describe-environments.html
     * [^7]: https://docs.aws.amazon.com/elasticloadbalancing/2012-06-01/APIReference/API_DescribeLoadBalancers.html
     * [^8]: https://docs.aws.amazon.com/elasticloadbalancing/latest/APIReference/API_DescribeLoadBalancers.html
     * [^9]: http://docs.aws.amazon.com/cli/latest/reference/elb/describe-load-balancers.html
     * [^10]: http://docs.aws.amazon.com/cli/latest/reference/elbv2/describe-load-balancers.html
     * [^11]: https://docs.aws.amazon.com/global-accelerator/latest/api/API_DescribeAccelerator.html
     * [^12]: https://docs.aws.amazon.com/cli/latest/reference/globalaccelerator/describe-accelerator.html
     * [^13]: https://docs.aws.amazon.com/general/latest/gr/s3.html#s3_website_region_endpoints
     * [^14]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/getting-started.html
     *
     * @var string
     */
    private $dnsName;

    /**
     * *Applies only to alias, failover alias, geolocation alias, latency alias, and weighted alias resource record sets:*
     * When `EvaluateTargetHealth` is `true`, an alias resource record set inherits the health of the referenced Amazon Web
     * Services resource, such as an ELB load balancer or another resource record set in the hosted zone.
     *
     * Note the following:
     *
     * - `CloudFront distributions`:
     *
     *   You can't set `EvaluateTargetHealth` to `true` when the alias target is a CloudFront distribution.
     * - `Elastic Beanstalk environments that have regionalized subdomains`:
     *
     *   If you specify an Elastic Beanstalk environment in `DNSName` and the environment contains an ELB load balancer,
     *   Elastic Load Balancing routes queries only to the healthy Amazon EC2 instances that are registered with the load
     *   balancer. (An environment automatically contains an ELB load balancer if it includes more than one Amazon EC2
     *   instance.) If you set `EvaluateTargetHealth` to `true` and either no Amazon EC2 instances are healthy or the load
     *   balancer itself is unhealthy, Route 53 routes queries to other available resources that are healthy, if any.
     *
     *   If the environment contains a single Amazon EC2 instance, there are no special requirements.
     * - `ELB load balancers`:
     *
     *   Health checking behavior depends on the type of load balancer:
     *
     *   - **Classic Load Balancers**: If you specify an ELB Classic Load Balancer in `DNSName`, Elastic Load Balancing
     *     routes queries only to the healthy Amazon EC2 instances that are registered with the load balancer. If you set
     *     `EvaluateTargetHealth` to `true` and either no EC2 instances are healthy or the load balancer itself is
     *     unhealthy, Route 53 routes queries to other resources.
     *   - **Application and Network Load Balancers**: If you specify an ELB Application or Network Load Balancer and you
     *     set `EvaluateTargetHealth` to `true`, Route 53 routes queries to the load balancer based on the health of the
     *     target groups that are associated with the load balancer:
     *
     *     - For an Application or Network Load Balancer to be considered healthy, every target group that contains targets
     *       must contain at least one healthy target. If any target group contains only unhealthy targets, the load
     *       balancer is considered unhealthy, and Route 53 routes queries to other resources.
     *     - A target group that has no registered targets is considered unhealthy.
     *
     *
     *   > When you create a load balancer, you configure settings for Elastic Load Balancing health checks; they're not
     *   > Route 53 health checks, but they perform a similar function. Do not create Route 53 health checks for the EC2
     *   > instances that you register with an ELB load balancer.
     *
     * - `API Gateway APIs`:
     *
     *   There are no special requirements for setting `EvaluateTargetHealth` to `true` when the alias target is an API
     *   Gateway API. However, because API Gateway is highly available by design, `EvaluateTargetHealth` provides no
     *   operational benefit and Route 53 health checks [^1] are recommended instead for failover scenarios.
     * - `S3 buckets`:
     *
     *   There are no special requirements for setting `EvaluateTargetHealth` to `true` when the alias target is an S3
     *   bucket. However, because S3 buckets are highly available by design, `EvaluateTargetHealth` provides no operational
     *   benefit and Route 53 health checks [^2] are recommended instead for failover scenarios.
     * - `VPC interface endpoints`:
     *
     *   There are no special requirements for setting `EvaluateTargetHealth` to `true` when the alias target is a VPC
     *   interface endpoint. However, because VPC interface endpoints are highly available by design, `EvaluateTargetHealth`
     *   provides no operational benefit and Route 53 health checks [^3] are recommended instead for failover scenarios.
     * - `Other records in the same hosted zone`:
     *
     *   If the Amazon Web Services resource that you specify in `DNSName` is a record or a group of records (for example, a
     *   group of weighted records) but is not another alias record, we recommend that you associate a health check with all
     *   of the records in the alias target. For more information, see What Happens When You Omit Health Checks? [^4] in the
     *   *Amazon Route 53 Developer Guide*.
     *
     * > While `EvaluateTargetHealth` can be set to `true` for highly available Amazon Web Services services (such as S3
     * > buckets, VPC interface endpoints, and API Gateway), these services are designed for high availability and rarely
     * > experience outages that would be detected by this feature. For failover scenarios with these services, consider
     * > using Route 53 health checks [^5] that monitor your application's ability to access the service instead.
     *
     * For more information and examples, see Amazon Route 53 Health Checks and DNS Failover [^6] in the *Amazon Route 53
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
     * [^2]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
     * [^3]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
     * [^4]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-complex-configs.html#dns-failover-complex-configs-hc-omitting
     * [^5]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
     * [^6]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
     *
     * @var bool
     */
    private $evaluateTargetHealth;

    /**
     * @param array{
     *   HostedZoneId: string,
     *   DNSName: string,
     *   EvaluateTargetHealth: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->hostedZoneId = $input['HostedZoneId'] ?? $this->throwException(new InvalidArgument('Missing required field "HostedZoneId".'));
        $this->dnsName = $input['DNSName'] ?? $this->throwException(new InvalidArgument('Missing required field "DNSName".'));
        $this->evaluateTargetHealth = $input['EvaluateTargetHealth'] ?? $this->throwException(new InvalidArgument('Missing required field "EvaluateTargetHealth".'));
    }

    /**
     * @param array{
     *   HostedZoneId: string,
     *   DNSName: string,
     *   EvaluateTargetHealth: bool,
     * }|AliasTarget $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDnsName(): string
    {
        return $this->dnsName;
    }

    public function getEvaluateTargetHealth(): bool
    {
        return $this->evaluateTargetHealth;
    }

    public function getHostedZoneId(): string
    {
        return $this->hostedZoneId;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->hostedZoneId;
        $node->appendChild($document->createElement('HostedZoneId', $v));
        $v = $this->dnsName;
        $node->appendChild($document->createElement('DNSName', $v));
        $v = $this->evaluateTargetHealth;
        $node->appendChild($document->createElement('EvaluateTargetHealth', $v ? 'true' : 'false'));
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
