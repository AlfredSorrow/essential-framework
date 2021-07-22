test:
	vendor/bin/phpunit --colors=always
serve-local:
	php -S 172.19.0.2:80 public/index.php	
docker-local:
	export DOCKER_INTERNAL_IP="$(shell ip route | grep docker0 | awk '{print $$9}')"; \
	docker-compose up --build -d