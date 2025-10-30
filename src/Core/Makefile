.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker: start-docker-s3 start-docker-localstack
start-docker-localstack:
	docker start async_aws_localstack && exit 0 || \
	docker start async_aws_localstack-sts && exit 0 || \
	docker pull localstack/localstack:3.0.0 && \
	docker run -d -p 4566:4566 -e SERVICES=sts -v /var/run/docker.sock:/var/run/docker.sock --name async_aws_localstack-sts localstack/localstack:3.0.0 && \
	docker run --rm --link async_aws_localstack-sts:localstack martin/wait -c localstack:4566
start-docker-s3:
	docker pull asyncaws/testing-s3
	docker start async_aws_s3 && exit 0 || \
	docker run -d -p 4569:4569 -p 4570:4569 --name async_aws_s3 asyncaws/testing-s3

test: initialize
	./vendor/bin/phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-sts || true
	docker rm async_aws_localstack-sts || true
