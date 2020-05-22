SHELL := /bin/bash
COMPONENTS := $(shell find src -maxdepth 4 -type f -name Makefile  | sed  -e 's/\/Makefile//g' | sort)
SUBCLEAN = $(addsuffix .clean,$(COMPONENTS))
SUBINITIALIZE = $(addsuffix .initialize,$(COMPONENTS))

.PHONY: clean $(SUBCLEAN)
clean: $(SUBCLEAN)
$(SUBCLEAN): %.clean:
	$(MAKE) -C $* clean


.PHONY: initialize $(SUBINITIALIZE)
initialize: $(SUBINITIALIZE)
$(SUBINITIALIZE): %.initialize:
	$(MAKE) -C $* initialize

start-docker: initialize
stop-docker: clean

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
