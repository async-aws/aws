SHELL := /bin/bash
COMPONENTS := $(shell find src -maxdepth 4 -type f -name Makefile  | sed  -e 's/\/Makefile//g' | sort)
SUBCLEAN = $(addsuffix .clean,$(COMPONENTS))
SUBINITIALIZE = $(addsuffix .initialize,$(COMPONENTS))

.PHONY: clean $(SUBCLEAN)
clean: stop-docker $(SUBCLEAN)
$(SUBCLEAN): %.clean:
	$(MAKE) -C $* clean


.PHONY: initialize $(SUBINITIALIZE)
initialize: start-docker
	$(MAKE) $(SUBINITIALIZE)
$(SUBINITIALIZE): %.initialize:
	$(MAKE) -C $* initialize

start-docker: start-docker-s3 start-docker-kms start-docker-localstack
start-docker-localstack:
	docker pull localstack/localstack:4.9.2
	docker start async_aws_localstack && exit 0 || \
	docker run -d -p 4566:4566 -p 4567:4566 -p 4568:4566 -p 4571:4566 -p 4572:4566 -p 4573:4566 -p 4574:4566 -p 4575:4566 -p 4576:4566 -p 4577:4566 -p 4578:4566 -e SERVICES=sts,cloudformation,logs,events,iam,sns,ssm,dynamodb,route53,kinesis,secretsmanager -v /var/run/docker.sock:/var/run/docker.sock --name async_aws_localstack localstack/localstack:4.9.2 && \
	docker run --rm --link async_aws_localstack:localstack martin/wait -c localstack:4566
start-docker-s3:
	docker pull asyncaws/testing-s3
	docker start async_aws_s3 && exit 0 || \
	docker run -d -p 4569:4569 -p 4570:4569 --name async_aws_s3 asyncaws/testing-s3
start-docker-kms:
	docker pull nsmithuk/local-kms
	docker start async_aws_kms && exit 0 || \
	docker run -d -p 4579:8080 --name async_aws_kms nsmithuk/local-kms

stop-docker:
	docker stop async_aws_localstack || true
	docker rm async_aws_localstack || true
	docker stop async_aws_s3 || true
	docker rm async_aws_s3 || true
	docker stop async_aws_kms || true
	docker rm async_aws_kms || true

test: initialize
	./vendor/bin/phpunit

website-assets: website/template/assets/app.css
website/template/assets/app.css: website/node_modules website/assets/*
	cd website && ./node_modules/.bin/encore prod
website/node_modules: website/package.json
	cd website && npm install

website-post-process: website/node_modules
ifneq (,$(wildcard .couscous/generated/index.html))
	cd website && npm run post-process ${PWD}/.couscous/generated;
endif
