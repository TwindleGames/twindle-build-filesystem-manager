<?php
ini_set('display_errors', '1');
ini_set('html_errors', false);
error_reporting(E_ERROR);
require_once(__DIR__."/types/Release.php");
require_once(__DIR__."/types/ReleaseManager.php");
require_once(__DIR__."/types/Release.php");
require_once(__DIR__."/impl/FilesystemReleaseGenerator.php");

$release = new TwindleGames\Release();
$release->project = strval($_POST["project"]);
$release->environment = strval($_POST["environment"]);
$release->platforms = explode("|", strval($_POST["platforms"]));
$release->buildNumber = intval($_POST["buildNumber"]);

$missingProps = $release->getMissingProperties();
if (count($missingProps) > 0) {
  header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
  echo "ERROR: Missing required properties: " . implode(", ", $missingProps);
  die;
}

$config = json_decode(file_get_contents(__DIR__."/config.json"));
if ($_POST["secret"] !== $config->secret) {
  header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
  echo "ERROR: Must supply API key to generate new release.";
  die;
}

$manager = new TwindleGames\ReleaseManager(new TWindleGames\FilesystemReleaseGenerator());
$manager->baseDirectory = $config->baseDirectory;
$manager->publishRelease($release);
die;