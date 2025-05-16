build:
	docker compose build --force-rm

down:
	docker compose down

up:
	docker compose up -d

install: remove-sources down up download-empty-project

enter: 
	docker compose exec symfony bash

restart-nginx:
	docker compose exec nginx nginx -s reload
