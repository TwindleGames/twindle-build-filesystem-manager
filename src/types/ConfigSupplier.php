<?php
namespace TwindleGames;

/**
 * Used to abstract the process of fetching config
 */
interface ConfigSupplier {
  public function getBaseDirectory();
}