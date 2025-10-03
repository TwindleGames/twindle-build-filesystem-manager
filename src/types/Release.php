<?php
namespace TwindleGames;

/**
 * Represents a new release to be deployed
 */
class Release {
  /**
   * The project name
   */
  public string $project;

  /**
   * An array of platform names
   */
  public array $platforms = ["web", "windows", "linux"];

  /**
   * The environment name
   */
  public string $environment;

  /**
   * The build number
   */
  public int $buildNumber;


  /**
   * Generate paths to be created to deploy this release
   */
  public function getDeployPaths(): array {
    $obj = $this;
    return array_map(function($platform) {
      return implode("/", [$this->project, $this->environment, $platform, $this->buildNumber]);
    }, $this->platforms);
  }

  public function getMissingProperties(): array {
    $properties = array_keys(get_class_vars(get_class($this)));
    return array_filter($properties, function ($prop) {
      if (isset($this->$prop)) {
        if (is_array($this->$prop)) {
          return empty(implode("", $this->$prop));
        }
      }
      return !isset($this->$prop) || empty($this->$prop);
    });
  }
}