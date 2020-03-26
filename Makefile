SHELL := /bin/bash
COMPONENTS := $(shell find src -maxdepth 4 -type f -name Makefile  | sed  -e 's/\/Makefile//g' | sort)

initialize: start-docker
start-docker:
	PWD=pwd
	set -e; \
	for COMPONENT in $(COMPONENTS); do \
		echo "###############################################"; \
		echo "###############################################"; \
		echo "### Starting docker for $${COMPONENT}"; \
		echo "###"; \
		cd ${PWD} ; cd $${COMPONENT} ; \
		make initialize; \
		echo ""; \
	done

test: initialize
	./vendor/bin/simple-phpunit

clean: stop-docker
stop-docker:
	PWD=pwd
	set -e; \
	for COMPONENT in $(COMPONENTS); do \
		echo "###############################################"; \
		echo "###############################################"; \
		echo "### Stopping docker on$${COMPONENT}"; \
		echo "###"; \
		cd ${PWD} ; cd $${COMPONENT} ; \
		make stop-docker; \
		echo ""; \
	done


website-assets: website/template/assets/app.css
website/template/assets/app.css: website/node_modules website/assets/*
	cd website && ./node_modules/.bin/encore prod
website/node_modules: website/package.json
	cd website && npm install
