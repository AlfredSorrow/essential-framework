test:
	vendor/bin/phpunit --colors=always
serve-local:
	php -S localhost:8080 -t public public/index.php