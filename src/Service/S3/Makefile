.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_s3 && exit 0 || \
	docker start async_aws_s3-client && exit 0 || \
	docker pull asyncaws/testing-s3 && \
	docker run -d -p 4569:4569 --name async_aws_s3-client asyncaws/testing-s3

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_s3-client || true
	docker rm async_aws_s3-client || true
