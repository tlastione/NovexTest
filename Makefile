install:
	make up
	make composer-install
	make migrate

up: 
	@docker compose up -d

down: 
	@docker compose down --remove-orphans

re: 
	@make down
	@make up
	
cc:
	@docker compose exec --user=application app php /app/bin/console cache:clear

composer-install:
	@docker compose exec app sh -c "cd /app && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer && composer install"

migrate:
	@docker compose exec --user=application app php /app/bin/console doctrine:migrations:migrate --no-interaction

migration:
	@docker compose exec --user=application app php /app/bin/console make:migration