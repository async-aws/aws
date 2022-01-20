.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_localstack && exit 0 || \
	docker start async_aws_localstack-kms && exit 0 || \
	docker pull localstack/localstack && \
	docker run -d -p 4579:8080 --name async_aws_localstack-kms nsmithuk/local-kms && \
	docker run --rm --link async_aws_localstack-kms:localstack martin/wait -c localstack:8080

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-kms || true
