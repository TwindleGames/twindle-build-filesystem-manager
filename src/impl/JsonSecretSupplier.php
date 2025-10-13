<?php
namespace TwindleGames;
require_once(__DIR__."/../types/SecretSupplier.php");
require_once(__DIR__."/BaseJsonSupplier.php");

class JsonSecretSupplier extends BaseJsonSupplier implements SecretSupplier {
  public function getSecret() {
    return $this->config->secret;
  }
}