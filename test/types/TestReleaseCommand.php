<?php
require_once(__DIR__."/../../src/types/ReleaseGenerator.php");

class TestReleaseCommand implements TwindleGames\ReleaseGenerator {
  public array $createdDirectories = [];
  public string $baseDirectory;

  public function createDirectory(string $dirname): void {
    array_push($this->createdDirectories, $dirname);
  }
}
