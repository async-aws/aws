SHELL := /bin/bash
COMPONENTS := $(shell find src -maxdepth 4 -type f -name Makefile  | sed  -e 's/\/Makefile//g' | sort)
TOPTARGETS := start-docker stop-docker

$(TOPTARGETS): $(COMPONENTS)
$(COMPONENTS):
	$(MAKE) -C $@ $(MAKECMDGOALS)

.PHONY: $(TOPTARGETS) $(COMPONENTS)

initialize: start-docker
clean: stop-docker

test: initialize
	./vendor/bin/simple-phpunit

website-assets: website/template/assets/app.css
website/template/assets/app.css: website/node_modules website/assets/*
	cd website && ./node_modules/.bin/encore prod
website/node_modules: website/package.json
	cd website && npm install

website-post-process: website/node_modules
ifneq (,$(wildcard .couscous/generated/index.html))
	cd website && npm run post-process ${PWD}/.couscous/generated;
endif
