<?php
namespace TwindleGames;
require_once(__DIR__."/../types/InvalidSecretException.php");
require_once(__DIR__."/../types/MissingDependencyException.php");
require_once(__DIR__."/../types/MissingParameterException.php");
require_once(__DIR__."/../types/Release.php");
require_once(__DIR__."/../types/ReleaseManager.php");


class CreateRelease {
  protected $secrets;
  protected $generator;
  protected $config;

  private $required_params = ["secret", "project", "environment", "platforms", "buildNumber"];

  public function __construct(array $dependencies) {
    if (
      !isset($dependencies["secrets"]) || 
      !isset($dependencies["generator"]) ||
      !method_exists($dependencies["secrets"], "getSecret") ||
      !method_exists($dependencies["generator"], "createDirectory")
    ) {
      throw new MissingDependencyException("Need to supply valid `SecretSupplier` and `ReleaseGenerator` dependencies.");
    }
    $this->secrets = $dependencies["secrets"];
    $this->generator = $dependencies["generator"];
    if (isset($dependencies["config"]) && method_exists($dependencies["config"], "getBaseDirectory")) {
      $this->config = $dependencies["config"];
    }
  }

  public function createRelease(array $args) {
    $this->validateReleaseParameters($args);
    $release = $this->createReleaseObject($args);
    $this->publishRelease($release);
  }

  private function validateReleaseParameters(array $args) {
    foreach ($this->required_params as $param) {
      if (!isset($args[$param])) {
        throw new MissingParameterException("Missing required parameter: " . $param);
      }
    }
    if ($args["secret"] !== $this->secrets->getSecret()) {
      throw new InvalidSecretException();
    }
  }

  private function createReleaseObject(array $args) {
    $release = new Release();
    $release->project = strval($args["project"]);
    $release->environment = strval($args["environment"]);
    $release->platforms = explode("|", strval($args["platforms"]));
    $release->buildNumber = intval($args["buildNumber"]);
    $missingProps = $release->getMissingProperties();
    if (count($missingProps) > 0) {
      throw new MissingParameterException("Missing required parameters: " . implode($missingProps, ", "));
    }
    return $release;
  }

  private function publishRelease(Release $release) {
    $manager = new ReleaseManager($this->generator);
    if ($this->config) {
      $manager->baseDirectory = $this->config->getBaseDirectory();
    }
    $manager->publishRelease($release);
  }
}