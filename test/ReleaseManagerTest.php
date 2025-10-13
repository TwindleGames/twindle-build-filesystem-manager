<?php
require_once(__DIR__."/../src/types/ReleaseGenerator.php");
require_once(__DIR__."/../src/types/ReleaseManager.php");
require_once(__DIR__."/../src/types/Release.php");
require_once(__DIR__."/types/TestReleaseCommand.php");
use PHPUnit\Framework\TestCase;
use TwindleGames\ReleaseGenerator;
use TwindleGames\ReleaseManager;
use TwindleGames\Release;

final class ReleaseManagerTest extends TestCase {
  protected TestReleaseCommand $generator;
  protected ReleaseManager $manager;
  protected Release $release;

  public function setUp(): void {
    $this->generator = new TestReleaseCommand();
    $this->manager = new ReleaseManager($this->generator);
    $this->release = new Release();
    $this->release->project = "kimchi-noodles";
    $this->release->platforms = ["windows", "linux", "web"];
    $this->release->environment = "dev";
    $this->release->buildNumber = 420;
  }

  public function testItMakeARelease(): void {
    $this->manager->publishRelease($this->release);

    $this->assertContains("kimchi-noodles/dev/web/420", $this->generator->createdDirectories);
    $this->assertContains("kimchi-noodles/dev/linux/420", $this->generator->createdDirectories);
    $this->assertContains("kimchi-noodles/dev/windows/420", $this->generator->createdDirectories);
  }

  public function testItCanApplyConfigToRelease(): void {
    $this->manager->baseDirectory = "/base/directory/test";
    $this->manager->publishRelease($this->release);

    $this->assertContains("/base/directory/test/kimchi-noodles/dev/web/420", $this->generator->createdDirectories);
  }
}