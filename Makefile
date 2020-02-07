SHELL := /bin/bash
COMPONENTS := $(shell find src -maxdepth 4 -type f -name Makefile  | sed  -e 's/\/Makefile//g' | sort)

# Initialize all compoennts the PHP runtimes
initialize:
	PWD=pwd
	set -e; \
	for COMPONENT in $(COMPONENTS); do \
		echo "###############################################"; \
		echo "###############################################"; \
		echo "### Initialize $${COMPONENT}"; \
		echo "###"; \
		cd ${PWD} ; cd $${COMPONENT} ; \
		make initialize; \
		echo ""; \
	done

test: initialize
	./vendor/bin/simple-phpunit

stop-docker:
	PWD=pwd
	set -e; \
	for COMPONENT in $(COMPONENTS); do \
		echo "###############################################"; \
		echo "###############################################"; \
		echo "### Initialize $${COMPONENT}"; \
		echo "###"; \
		cd ${PWD} ; cd $${COMPONENT} ; \
		make stop-docker; \
		echo ""; \
	done
