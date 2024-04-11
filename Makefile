install:
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

gendiff:
	bin/gendiff

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

fix-lint:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin


