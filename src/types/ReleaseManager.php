<?php
namespace TwindleGames;
require_once(__DIR__."/ReleaseGenerator.php");
require_once(__DIR__."/Release.php");

/**
 * Takes in a release and sends its deploy paths to a `ReleaseGenerator` to publish a new release.
 */
class ReleaseManager {
  public $baseDirectory;
  private $generator;

  public function __construct(ReleaseGenerator $generator) {
    $this->generator = $generator;
  }

  /**
   * Create the directories for a new release
   */
  public function publishRelease(Release $release) {
    $paths = $release->getDeployPaths();
    foreach ($paths as $path) {
      $this->generator->createDirectory($this->getFullPath($path));
    }
  }

  private function getFullPath(string $path) {
    if (isset($this->baseDirectory)) {
      return $this->baseDirectory . "/" . $path;
    }
    return $path;
  }
}