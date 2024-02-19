.PHONY: init run-integration-tests remove-media-cache-tests

install-database:
	mariadb -hmysql -uroot -proot -e 'DROP DATABASE IF EXISTS 7days_test_task2'
	mariadb -hmysql -uroot -proot -e 'CREATE DATABASE 7days_test_task2'
	php bin/console d:m:m --no-interaction
	php bin/console app:generate-random-post
	php bin/console app:generate-random-post
