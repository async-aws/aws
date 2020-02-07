.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker pull lphoward/fake-s3
	docker start async_aws_s3 || docker run -d -p 4569:4569 --name async_aws_s3 lphoward/fake-s3

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_s3 || true
