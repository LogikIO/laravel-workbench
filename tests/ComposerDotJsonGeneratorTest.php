<?php

use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\Generators\ComposerDotJsonGenerator;

class ComposerDotJsonGeneratorTest extends TestCase {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $path;
    protected $composerDotJsonFile;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = app(Filesystem::class);

        $this->path = __DIR__;
        $this->composerDotJsonFile = $this->path . DIRECTORY_SEPARATOR . 'composer.json';
    }

    public function tearDown()
    {
        $this->assertTrue($this->filesystem->delete($this->composerDotJsonFile));
    }

    public function testItGeneratesAComposerDotJsonFile()
    {
        $generator = app(ComposerDotJsonGenerator::class);
        $generator->generate(
            $this->path,
            'test-vendor',
            'test-package',
            'This is a test file.',
            'MIT',
            'John Doe',
            'john.doe@example.com',
            'TestVendor\\TestPackage'
        );

        $this->assertFileExists($this->composerDotJsonFile);

        $json = json_encode([
            'name' => 'test-vendor' . DIRECTORY_SEPARATOR . 'test-package',
            'description' => 'This is a test file.',
            'license' => 'MIT',
            'authors' => [
                ['name' => 'John Doe', 'email' => 'john.doe@example.com']
            ],
            'autoload' => [
                'psr-4' => [
                    'TestVendor\\TestPackage\\' => 'src/'
                ]
            ]
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

        $this->assertJsonStringEqualsJsonFile($this->composerDotJsonFile, $json);
    }

}