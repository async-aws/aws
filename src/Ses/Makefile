.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker pull jdelibas/aws-ses-local
	docker start async_aws_ses || docker run -d -p 9001:9001 --name async_aws_ses docker pull jdelibas/aws-ses-local

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_ses || true
