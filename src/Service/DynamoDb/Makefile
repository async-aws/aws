.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker pull amazon/dynamodb-local
	docker start async_aws_dynamodb || docker run -d -p 8000:8000 --name async_aws_dynamodb amazon/dynamodb-local

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_dynamodb || true
