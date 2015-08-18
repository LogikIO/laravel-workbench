<?php

use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\Exceptions\DirectoryNotCreated;
use Speelpenning\Workbench\Exceptions\PackageExists;
use Speelpenning\Workbench\Generators\PackageGenerator;

class PackageGeneratorTest extends TestCase {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $path;
    protected $vendorPath;
    protected $packagePath;
    protected $sourcePath;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = app(Filesystem::class);

        $this->path = __DIR__;
        $this->vendorPath = $this->path . DIRECTORY_SEPARATOR . 'acme';
        $this->packagePath = $this->vendorPath . DIRECTORY_SEPARATOR . 'testing';
        $this->sourcePath = $this->packagePath . DIRECTORY_SEPARATOR . 'src';
    }

    public function tearDown()
    {
        $this->filesystem->deleteDirectory($this->vendorPath);
    }

    public function testItGeneratesThePackageDirectories()
    {
        $generator = app(PackageGenerator::class);
        $generator->generate($this->path, 'acme', 'testing');

        $this->assertEquals($this->packagePath, $generator->getPackagePath());
        $this->assertEquals($this->sourcePath, $generator->getSourcePath());
    }

    public function testExistingPathThrowsPackageExists()
    {
        $this->setExpectedException(PackageExists::class);

        app(PackageGenerator::class)->generate($this->path, 'acme', 'testing');
        app(PackageGenerator::class)->generate($this->path, 'acme', 'testing');
    }

    public function testNotAllowedPathThrowsDirectoryNotCreated()
    {
        $this->setExpectedException(DirectoryNotCreated::class);

        app(PackageGenerator::class)->generate('/usr', 'acme', 'testing');
    }

}
