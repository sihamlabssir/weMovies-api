DOCKER_COMPOSE=docker compose
COMPOSER=composer

.DEFAULT_GOAL := help
.PHONY: help

help: ## Show help page of this makefile, listing all available tasks and their description.
	@echo -e "Usage:\n  make <target>\n"
	@awk 'BEGIN {FS = ":.##"} /^[a-zA-Z_-]+:.?##/ { printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2 } /^##/ { printf "\033[33m%-15s\033[0m\n", substr($$0, 4) }' $(MAKEFILE_LIST)

## building & running
.PHONY: build start stop

build: ## Build containers
	$(DOCKER_COMPOSE) build

start: ## Start containers
	$(DOCKER_COMPOSE) up -d

stop: ## Stop containers
	$(DOCKER_COMPOSE) down

## code analysis/quality
.PHONY: phpstan phpcs csfix

phpstan: ## Perform a static codebase analysis
	$(DOCKER_COMPOSE) exec php $(COMPOSER) run phpcs

phpcs: ## Check the coding style
	$(DOCKER_COMPOSE) exec php $(COMPOSER) run phpcs

phpcsfix: ## Fix PHP Coding Standards
	$(DOCKER_COMPOSE) exec php $(COMPOSER) run phpcsfix

## test
.PHONY: test test-coverage

test: ## Runs all tests
	$(DOCKER_COMPOSE) exec php ./vendor/bin/phpunit