composer.phar:
	@curl -s http://getcomposer.org/installer | php

install: composer.phar
	@echo Installing...
	@php composer.phar install --dev

update: composer.phar
	@echo "Updating..."
	@php composer.phar self-update
	@php composer.phar update

test:
	@./bin/atoum -bf ./tests/bootstrap.php -d ./tests/units

test-loop:
	@./bin/atoum --loop -bf ./tests/bootstrap.php -d ./tests/units

clean:
	@echo "Cleaning..."
	@rm composer.phar

.PHONY: test
