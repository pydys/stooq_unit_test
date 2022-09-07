


build:
	@docker-compose up --build -d --force-recreate

down:
	@docker-compose down --volumes

app-bash:
	@docker-compose exec app /bin/bash

ccl:
	@docker-compose down --volumes