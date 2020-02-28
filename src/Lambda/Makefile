.EXPORT_ALL_VARIABLES:

ROOT_DIR:=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

initialize: start-docker
start-docker:
	docker pull lambci/lambda
	docker start async_aws_lambda || docker run -d -p 9001:9001 -e DOCKER_LAMBDA_STAY_OPEN=1 -v "$(ROOT_DIR)/tests/fixtures/lambda":/var/task:ro,delegated --name async_aws_lambda lambci/lambda:nodejs12.x index.handler

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_lambda || true
