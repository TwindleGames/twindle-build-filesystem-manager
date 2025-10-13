<?php
ini_set('display_errors', '1');
ini_set('html_errors', false);
error_reporting(E_ALL);
require_once(__DIR__."/app/CreateRelease.php");
require_once(__DIR__."/impl/FilesystemReleaseGenerator.php");
require_once(__DIR__."/impl/JsonConfigSupplier.php");
require_once(__DIR__."/impl/JsonSecretSupplier.php");

$configPath = __DIR__."/config.json";
$createRelease = new TwindleGames\CreateRelease([
  'generator' => new TwindleGames\FilesystemReleaseGenerator(),
  'secrets' => new TwindleGames\JsonSecretSupplier($configPath),
  'config' => new TwindleGames\JsonConfigSupplier($configPath),
]);
try {
  $createRelease->createRelease($_POST);
  die;
} catch (TwindleGames\InvalidSecretException $c) {
  header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
  echo "ERROR: Must supply API key to generate new release.";
  die;
} catch (TwindleGames\MissingParameterException $c) {
  header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
  echo "Missing parameters";
  die;
}