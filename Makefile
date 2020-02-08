SHELL := /bin/bash
COMPONENTS := $(shell find src -maxdepth 4 -type f -name Makefile  | sed  -e 's/\/Makefile//g' | sort)

initialize: start-docker
start-docker:
	docker pull localstack/localstack
	docker start async_aws || (docker run -d -p 4567-4584:4567-4584 --name async_aws localstack/localstack && sleep 5)

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws || true
