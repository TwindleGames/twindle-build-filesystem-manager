<?php
require_once(__DIR__."/../src/types/Release.php");
use PHPUnit\Framework\TestCase;
use TwindleGames\Release;

final class ReleaseTest extends TestCase {
  public function testItCanGenerateDeployDirectoryPaths(): void {
    $release = new Release();
    $release->project = "kimchi-noodles";
    $release->platforms = ["windows", "linux", "web"];
    $release->environment = "dev";
    $release->buildNumber = 420;

    $paths = $release->getDeployPaths();
    $this->assertCount(3, $paths);
    $this->assertContains("kimchi-noodles/dev/web/420", $paths);
    $this->assertContains("kimchi-noodles/dev/linux/420", $paths);
    $this->assertContains("kimchi-noodles/dev/windows/420", $paths);
  }

  public function testItCanCheckValidity(): void {
    $release = new Release();
    $release->project = "kimchi-noodles";
    $release->platforms = ["windows", "linux", "web"];
    $release->environment = "dev";
    $release->buildNumber = 420;

    $this->assertCount(0, $release->getMissingProperties());

    unset($release->project);
    $this->assertEquals(["project"], $release->getMissingProperties());

    unset($release->platforms);
    $this->assertEquals(["project", "platforms"], $release->getMissingProperties());

    unset($release->environment);
    $this->assertEquals(["project", "platforms", "environment"], $release->getMissingProperties());

    unset($release->buildNumber);
    $this->assertEquals(["project", "platforms", "environment", "buildNumber"], $release->getMissingProperties());
  }

  public function testValidityChecksEmptiness(): void {
    $release = new Release();
    $release->project = "";
    $release->platforms = [];
    $release->environment = "";
    $release->buildNumber = 0;

    $this->assertEquals(["project", "platforms", "environment", "buildNumber"], $release->getMissingProperties());
  }
}