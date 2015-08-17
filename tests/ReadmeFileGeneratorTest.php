<?php

use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\Generators\ReadmeFileGenerator;

class ReadmeFileGeneratorTest extends TestCase {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $path;
    protected $readmeFile;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = app(Filesystem::class);

        $this->path = __DIR__;
        $this->readmeFile = $this->path . DIRECTORY_SEPARATOR . 'README.md';
    }

    public function tearDown()
    {
        $this->filesystem->delete($this->readmeFile);
    }

    public function testItGeneratesAReadmeFile()
    {
        app(ReadmeFileGenerator::class)->generate($this->path, 'testing', 'Unit testing');

        $this->assertFileExists($this->readmeFile);

        $readme = '# Testing

Unit testing

';
        $this->assertStringEqualsFile($this->readmeFile, $readme);
    }

}
