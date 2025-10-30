.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_kms && exit 0 || \
	docker start async_aws_localstack-kms && exit 0 || \
	docker pull nsmithuk/local-kms && \
	docker run -d -p 4579:8080 --name async_aws_localstack-kms nsmithuk/local-kms && \
	docker run --rm --link async_aws_localstack-kms:localstack martin/wait -c localstack:8080

test: initialize
	./vendor/bin/phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-kms || true
	docker rm async_aws_localstack-kms || true
