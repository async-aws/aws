.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker pull asyncaws/testing-s3
	docker start async_aws_flysystem_s3 || docker run -d -p 4570:4569 --name async_aws_flysystem_s3 asyncaws/testing-s3

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_flysystem_s3 || true
