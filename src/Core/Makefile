.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_localstack && exit 0 || \
	docker start async_aws_localstack-sts && exit 0 || \
	docker pull localstack/localstack:0.14.2 && \
	docker run -d -p 4566:4566 -e SERVICES=sts -v /var/run/docker.sock:/var/run/docker.sock --name async_aws_localstack-sts localstack/localstack:0.14.2 && \
	docker run --rm --link async_aws_localstack-sts:localstack martin/wait -c localstack:4566

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-sts || true
	docker rm async_aws_localstack-sts || true
