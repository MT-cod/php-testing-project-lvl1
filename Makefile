install:
	composer install
validate:
	composer validate
lint:
	composer run-script phpcs -- --standard=PSR12 bin src tests
test:
	composer exec --verbose phpunit tests
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover clover.xml --verbose
