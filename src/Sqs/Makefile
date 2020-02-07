.EXPORT_ALL_VARIABLES:

# Build the PHP runtimes
initialize:
	docker pull feathj/fake-sqs
	docker start async_aws_sqs || docker run -d -p 9494:9494 --name async_aws_sqs feathj/fake-sqs

test: initialize
	./vendor/bin/simple-phpunit

stop-docker:
	docker stop async_aws_sqs || true
