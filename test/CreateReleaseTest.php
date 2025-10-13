<?php
require_once(__DIR__."/../src/types/MissingDependencyException.php");
require_once(__DIR__."/../src/types/MissingParameterException.php");
require_once(__DIR__."/../src/types/ConfigSupplier.php");
require_once(__DIR__."/../src/types/SecretSupplier.php");
require_once(__DIR__."/../src/app/CreateRelease.php");
require_once(__DIR__."/types/TestReleaseCommand.php");
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use TwindleGames\CreateRelease;
use TwindleGames\MissingDependencyException;
use TwindleGames\MissingParameterException;
use TwindleGames\ConfigSupplier;
use TwindleGames\SecretSupplier;

class TestSecretSupplier implements SecretSupplier {
  public function getSecret() {
    return "potato";
  }
}

class TestConfigSupplier implements ConfigSupplier {
  public function getBaseDirectory() {
    return "/builds";
  }
}

final class CreateReleaseTest extends TestCase {
  protected $generator;
  protected $createRelease;

  public function setUp(): void {
    $this->generator = new TestReleaseCommand();
    $this->createRelease = new CreateRelease([
      'secrets' => new TestSecretSupplier(),
      'generator' => $this->generator,
    ]);
  }

  #[TestWith([[]])]
  #[TestWith([['potato' => 'potato']])]
  #[TestWith([['secrets' => new TestSecretSupplier()]])]
  #[TestWith([['generator' => new TestReleaseCommand()]])]
  #[TestWith([['secrets' => new TestReleaseCommand(), 'generator' => new TestSecretSupplier()]])]
  public function testItNeedsDependencies(array $deps): void {
    try {
      $createRelase = new CreateRelease($deps);
      $this->assertTrue(false, "Did not throw exception when creating invalid CreateRelease");
    } catch (MissingDependencyException $c) {
      $this->assertTrue(true);
    }
  }

  #[TestWith([[]])]
  #[TestWith([["project" => "test"]])]
  #[TestWith([["environment" => "dev"]])]
  #[TestWith([["platforms" => "windows|linux"]])]
  #[TestWith([["buildNumber" => 24]])]
  #[TestWith([["secret" => "potato"]])]
  public function testItValidatesInput(array $input): void {
    $args = $input;
    try {
      $this->createRelease->createRelease($args);
      $this->assertTrue(false); // We should have thrown an exception
    } catch (MissingParameterException $c) {
      $this->assertTrue(!!$c);
    }
  }

  public function testItChecksTheSecretWithTheProvider(): void {
    $args = [
      "project" => "test",
      "environment" => "phpunit",
      "platforms" => "windows|linux",
      "buildNumber" => 24,
      "secret" => "not a valid secret"
    ];
    try {
      $this->createRelease->createRelease($args);
      $this->assertFalse(true, "CreateRelease accepted an invalid secret");
    } catch (TwindleGames\InvalidSecretException $c) {
      $this->assertTrue(!!$c);
    }
  }

  public function testItCanGenerateANewRelease(): void {
    $args = [
      "project" => "test",
      "environment" => "phpunit",
      "platforms" => "windows|linux|potato-os|web",
      "buildNumber" => 24,
      "secret" => "potato"
    ];
    $this->createRelease->createRelease($args);
    $this->assertCount(4, $this->generator->createdDirectories);
    $this->assertContains("test/phpunit/windows/24", $this->generator->createdDirectories);
    $this->assertContains("test/phpunit/linux/24", $this->generator->createdDirectories);
    $this->assertContains("test/phpunit/web/24", $this->generator->createdDirectories);
    $this->assertContains("test/phpunit/potato-os/24", $this->generator->createdDirectories);
  }

  public function testItCanSupplyConfig(): void {
    $this->createRelease = $this->createRelease = new CreateRelease([
      'secrets' => new TestSecretSupplier(),
      'generator' => $this->generator,
      'config' => new TestConfigSupplier(),
    ]);
    $args = [
      "project" => "test",
      "environment" => "phpunit",
      "platforms" => "linux",
      "buildNumber" => 24,
      "secret" => "potato"
    ];
    $this->createRelease->createRelease($args);
    $this->assertCount(1, $this->generator->createdDirectories);
    $this->assertContains("/builds/test/phpunit/linux/24", $this->generator->createdDirectories);
  }
}
