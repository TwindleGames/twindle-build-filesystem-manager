<?php
namespace TwindleGames;

class BaseJsonSupplier {
  protected $config;

  public function __construct(string $pathToJson) {
    $this->config = json_decode(file_get_contents($pathToJson));
  }
}