.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker pull feathj/fake-sqs
	docker start async_aws_sqs || docker run -d -p 9494:9494 --name async_aws_sqs feathj/fake-sqs

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_sqs || true
