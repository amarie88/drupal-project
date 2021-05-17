<?php

// @codingStandardsIgnoreStart

use Robo\Result;
use Robo\Tasks;

/**
 * Base tasks for setting up a module to test within a full Drupal environment.
 *
 * @see http://robo.li/
 */
class RoboFile extends Tasks {

  /**
   * Command for Drupal Update.
   *
   * @return \Robo\Result
   *   The result of the collection of tasks.
   */
  public function drupalUpdate(): Result {

    // Create collection.
    $collection = $this->collectionBuilder();

    // Init tasks.
    $tasks = [];

    // Clear cache.
    $tasks[] = $this->drush()->arg('cache:rebuild');

    // Maintenance "On".
    $tasks[] = $this->drush()->args('state:set', 'system.maintenance', '1');

    // Drupal update.
    $tasks[] = $this->drush()->arg('updatedb')->option('no-post-updates')->option('yes');

    // Translation update.
    $tasks[] = $this->drush()->arg('locale:check');
    $tasks[] = $this->drush()->arg('locale:update');

    // Sync configuration.
    $tasks[] = $this->drush()->args('config:import', 'sync')->option('yes');

    // Drupal update.
    $tasks[] = $this->drush()->arg('updatedb')->option('yes');

    // Maintenance "Off".
    $tasks[] = $this->drush()->args('state:set', 'system.maintenance', '0');

    // Clear cache.
    $tasks[] = $this->drush()->arg('cache:rebuild');

    // Drupal status.
    $tasks[] = $this->drush()->arg('core:status');

    // Message.
    $collection->progressMessage("Drupal Update");

    // Add tasks in collection.
    $collection->addTaskList($tasks);

    return $collection->run();
  }

  /**
   * Return drush with default arguments.
   *
   * @return \Robo\Task\Base\Exec
   *   A drush exec command.
   */
  protected function drush() {
    return $this->taskExec('vendor/bin/drush');
  }

}
