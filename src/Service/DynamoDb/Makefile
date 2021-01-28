.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_localstack && exit 0 || \
	docker start async_aws_localstack-dynamodb && exit 0 || \
	docker pull localstack/localstack && \
	docker run -d -p 4575:4566 -e SERVICES=dynamodb -v /var/run/docker.sock:/var/run/docker.sock --name async_aws_localstack-dynamodb localstack/localstack && \
	docker run --rm --link async_aws_localstack-dynamodb:localstack martin/wait -c localstack:4566

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-dynamodb || true
