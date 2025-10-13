<?php
namespace TwindleGames;

/**
 * Used to abstract the process of fetching the secret
 */
interface SecretSupplier {
  public function getSecret();
}