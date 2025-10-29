.EXPORT_ALL_VARIABLES:

initialize: start-docker
start-docker:
	docker start async_aws_localstack && exit 0 || \
	docker start async_aws_localstack-sns && exit 0 || \
	docker pull localstack/localstack:4.9.2 && \
	docker run -d -p 4573:4566 -e SERVICES=sns -v /var/run/docker.sock:/var/run/docker.sock --name async_aws_localstack-sns localstack/localstack:4.9.2 && \
	docker run --rm --link async_aws_localstack-sns:localstack martin/wait -c localstack:4566

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	docker stop async_aws_localstack-sns || true
	docker rm async_aws_localstack-sns || true
