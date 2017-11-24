#!/usr/bin/env bash

printf 'Running PHPUnit tests.\n\n'
vendor/phpunit/phpunit/phpunit -c phpunit.xml --coverage-html coverage tests/
printf '\n\n--------------------------------\n\nRunning PHP Mess Detector.\n\n'
vendor/phpmd/phpmd/src/bin/phpmd . text ./standards/phpmd.xml --exclude tests,vendor,coverage,standards
printf '\n\n--------------------------------\n\nRunning PHP Copy-Paste Detector.\n\n'
vendor/sebastian/phpcpd/phpcpd . --exclude tests --exclude vendor --exclude coverage --exclude standards
printf '\n\n--------------------------------\n\nRunning PHPStan.\n\n'
vendor/bin/phpstan analyse -l 7 lib
printf '\n\n'
