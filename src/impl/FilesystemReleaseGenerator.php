<?php
namespace TwindleGames;
require_once(__DIR__."/../types/ReleaseGenerator.php");

/**
 * This is the ReleaseGenerator used in the main application.
 */
class FilesystemReleaseGenerator implements ReleaseGenerator {
  public function createDirectory(string $dirname): void {
    echo $dirname . "\n";
    if (!empty($dirname) && !is_dir($dirname)) {
      if (!mkdir($dirname, 0777, true)) {
        $error = error_get_last();
        throw new \Exception($error['message'] . " " . $dirname);
      }
    }
  }
}