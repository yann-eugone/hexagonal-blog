.SILENT:
.PHONY: help
ENV?=dev

# Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)


# ====================
# Install rules

install: orm.install cc
update: orm.update cc


# ====================
# Doctrine ORM rules

## Database install
orm.install:
	bin/console --env=$(ENV) doctrine:database:drop --if-exists --force
	bin/console --env=$(ENV) doctrine:database:create
	bin/console --env=$(ENV) doctrine:schema:create
	bin/console --env=$(ENV) doctrine:fixtures:load --no-interaction

## Database update
orm.update:
	bin/console --env=$(ENV) doctrine:schema:update --force


# ====================
# Other rules

## Cache clear
cc:
	bin/console --env=$(ENV) cache:clear

## Assets
assets:
	bin/console --env=$(ENV) assets:install --symlink --relative
