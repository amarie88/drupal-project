## phpcs
.PHONY: phpcs
phpcs:
	vendor/bin/robo drupal-coding:phpcs

## drupal_check
.PHONY: drupal_check
drupal_check:
	vendor/bin/robo drupal-coding:check
