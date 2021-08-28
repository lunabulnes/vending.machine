PROJECT := vending_machine
DOCKER_COMPOSE_FILE := ./docker-compose.yml

start:
	@docker-compose -f ./docker-compose.yml up -d --build

stop:
	@docker-compose -f ${DOCKER_COMPOSE_FILE} kill ${PROJECT}

logs:
	@docker-compose -f ${DOCKER_COMPOSE_FILE} logs ${PROJECT}

dev: start logs

enter:
	@docker-compose exec -u root ${PROJECT} /bin/sh

test:
	@docker-compose exec ${PROJECT} sh -c "./vendor/bin/phpunit --configuration=phpunit.xml"

run:
	@docker-compose exec ${PROJECT} sh -c "php ./src/RunCli.php $$COMMAND"
