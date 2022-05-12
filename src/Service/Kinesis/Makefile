.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_localstack && exit 0 || \
	docker start async_aws_localstack-kinesis && exit 0 || \
	docker pull localstack/localstack:0.14.2 && \
	docker run -d -p 4578:4566 -e SERVICES=kinesis -v /var/run/docker.sock:/var/run/docker.sock --name async_aws_localstack-kinesis localstack/localstack:0.14.2 && \
	docker run --rm --link async_aws_localstack-kinesis:localstack martin/wait -c localstack:4566

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-kinesis || true
