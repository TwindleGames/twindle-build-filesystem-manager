<?php
namespace TwindleGames;

/**
 * A humble object that represents filesystem operations
 */
interface ReleaseGenerator {
  public function createDirectory(string $dirname): void;
}