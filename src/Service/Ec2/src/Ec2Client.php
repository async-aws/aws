<?php

namespace AsyncAws\Ec2;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Ec2\Input\DeleteSnapshotRequest;
use AsyncAws\Ec2\Input\DeregisterImageRequest;
use AsyncAws\Ec2\Input\DescribeImagesRequest;
use AsyncAws\Ec2\Result\DeregisterImageResult;
use AsyncAws\Ec2\Result\DescribeImagesResult;
use AsyncAws\Ec2\ValueObject\Filter;

class Ec2Client extends AbstractApi
{
    /**
     * Deletes the specified snapshot.
     *
     * When you make periodic snapshots of a volume, the snapshots are incremental, and only the blocks on the device that
     * have changed since your last snapshot are saved in the new snapshot. When you delete a snapshot, only the data not
     * needed for any other snapshot is removed. So regardless of which prior snapshots have been deleted, all active
     * snapshots will have access to all the information needed to restore the volume.
     *
     * You cannot delete a snapshot of the root device of an EBS volume used by a registered AMI. You must first deregister
     * the AMI before you can delete the snapshot.
     *
     * For more information, see Delete an Amazon EBS snapshot [^1] in the *Amazon EBS User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/ebs/latest/userguide/ebs-deleting-snapshot.html
     *
     * @see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DeleteSnapshot.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ec2-2016-11-15.html#deletesnapshot
     *
     * @param array{
     *   SnapshotId: string,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * }|DeleteSnapshotRequest $input
     */
    public function deleteSnapshot($input): Result
    {
        $input = DeleteSnapshotRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteSnapshot', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Deregisters the specified AMI. A deregistered AMI can't be used to launch new instances.
     *
     * If a deregistered EBS-backed AMI matches a Recycle Bin retention rule, it moves to the Recycle Bin for the specified
     * retention period. It can be restored before its retention period expires, after which it is permanently deleted. If
     * the deregistered AMI doesn't match a retention rule, it is permanently deleted immediately. For more information, see
     * Recover deleted Amazon EBS snapshots and EBS-backed AMIs with Recycle Bin [^1] in the *Amazon EBS User Guide*.
     *
     * When deregistering an EBS-backed AMI, you can optionally delete its associated snapshots at the same time. However,
     * if a snapshot is associated with multiple AMIs, it won't be deleted even if specified for deletion, although the AMI
     * will still be deregistered.
     *
     * Deregistering an AMI does not delete the following:
     *
     * - Instances already launched from the AMI. You'll continue to incur usage costs for the instances until you terminate
     *   them.
     * - For EBS-backed AMIs: Snapshots that are associated with multiple AMIs. You'll continue to incur snapshot storage
     *   costs.
     * - For instance store-backed AMIs: The files uploaded to Amazon S3 during AMI creation. You'll continue to incur S3
     *   storage costs.
     *
     * For more information, see Deregister an Amazon EC2 AMI [^2] in the *Amazon EC2 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/recycle-bin.html
     * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/deregister-ami.html
     *
     * @see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DeregisterImage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ec2-2016-11-15.html#deregisterimage
     *
     * @param array{
     *   ImageId: string,
     *   DeleteAssociatedSnapshots?: bool|null,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * }|DeregisterImageRequest $input
     */
    public function deregisterImage($input): DeregisterImageResult
    {
        $input = DeregisterImageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeregisterImage', 'region' => $input->getRegion()]));

        return new DeregisterImageResult($response);
    }

    /**
     * Describes the specified images (AMIs, AKIs, and ARIs) available to you or all of the images available to you.
     *
     * The images available to you include public images, private images that you own, and private images owned by other
     * Amazon Web Services accounts for which you have explicit launch permissions.
     *
     * Recently deregistered images appear in the returned results for a short interval and then return empty results. After
     * all instances that reference a deregistered AMI are terminated, specifying the ID of the image will eventually return
     * an error indicating that the AMI ID cannot be found.
     *
     * When Allowed AMIs is set to `enabled`, only allowed images are returned in the results, with the `imageAllowed` field
     * set to `true` for each image. In `audit-mode`, the `imageAllowed` field is set to `true` for images that meet the
     * account's Allowed AMIs criteria, and `false` for images that don't meet the criteria. For more information, see
     * Allowed AMIs [^1].
     *
     * The Amazon EC2 API follows an eventual consistency model. This means that the result of an API command you run that
     * creates or modifies resources might not be immediately available to all subsequent commands you run. For guidance on
     * how to manage eventual consistency, see Eventual consistency in the Amazon EC2 API [^2] in the *Amazon EC2 Developer
     * Guide*.
     *
     * ! We strongly recommend using only paginated requests. Unpaginated requests are susceptible to throttling and
     * ! timeouts.
     *
     * > The order of the elements in the response, including those within nested structures, might vary. Applications
     * > should not assume the elements appear in a particular order.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-allowed-amis.html
     * [^2]: https://docs.aws.amazon.com/ec2/latest/devguide/eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeImages.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ec2-2016-11-15.html#describeimages
     *
     * @param array{
     *   ExecutableUsers?: string[]|null,
     *   ImageIds?: string[]|null,
     *   Owners?: string[]|null,
     *   IncludeDeprecated?: bool|null,
     *   IncludeDisabled?: bool|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   DryRun?: bool|null,
     *   Filters?: array<Filter|array>|null,
     *   '@region'?: string|null,
     * }|DescribeImagesRequest $input
     */
    public function describeImages($input = []): DescribeImagesResult
    {
        $input = DescribeImagesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeImages', 'region' => $input->getRegion()]));

        return new DescribeImagesResult($response, $this, $input);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
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
                    'endpoint' => "https://ec2.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://ec2.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://ec2-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://ec2-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://ec2-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://ec2-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://ec2-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://ec2-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://ec2.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://ec2.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://ec2.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://ec2.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'ec2',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://ec2.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'ec2',
            'signVersions' => ['v4'],
        ];
    }
}
