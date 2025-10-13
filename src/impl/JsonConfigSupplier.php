<?php
namespace TwindleGames;
require_once(__DIR__."/../types/ConfigSupplier.php");
require_once(__DIR__."/BaseJsonSupplier.php");

class JsonConfigSupplier extends BaseJsonSupplier implements ConfigSupplier {
  public function getBaseDirectory() {
    return $this->config->baseDirectory;
  }
}