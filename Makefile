install:
	php -i
	composer install

test: phpcs phpstan codecept

update:
	composer update
phpcs:
	./vendor/bin/phpcs
phpstan:
	./vendor/bin/phpstan analyse --memory-limit=-1
codecept:
	./vendor/bin/codecept run