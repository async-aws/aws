.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_localstack && exit 0 || \
	docker start async_aws_localstack-logs && exit 0 || \
	docker pull localstack/localstack && \
	docker run -d -p 4568:4566 -e SERVICES=logs -v /var/run/docker.sock:/var/run/docker.sock --name async_aws_localstack-logs localstack/localstack && \
	docker run --rm --link async_aws_localstack-logs:localstack martin/wait -c localstack:4566

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-logs || true
