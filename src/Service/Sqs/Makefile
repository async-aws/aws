.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_sqs && exit 0 || \
	docker pull asyncaws/testing-sqs && \
	docker run -d -p 9494:9494 --name async_aws_sqs asyncaws/testing-sqs

test: initialize
	./vendor/bin/phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_sqs || true
	docker rm async_aws_sqs || true
