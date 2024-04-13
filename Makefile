install:
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

gendiff:
	bin/gendiff --format style assets/file1.json assets/file2.json

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

fix-lint:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml


