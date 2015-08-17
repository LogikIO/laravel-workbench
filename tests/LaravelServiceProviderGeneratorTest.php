<?php

use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\Generators\LaravelServiceProviderGenerator;

class LaravelServiceProviderGeneratorTest extends TestCase {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $path;
    protected $serviceProviderFile;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = app(Filesystem::class);

        $this->path = __DIR__;
        $this->serviceProviderFile = $this->path . DIRECTORY_SEPARATOR . 'TestingServiceProvider.php';
    }

    public function tearDown()
    {
        $this->assertTrue($this->filesystem->delete($this->serviceProviderFile));
    }

    public function testItGeneratesAServiceProviderFile()
    {
        $generator = app(LaravelServiceProviderGenerator::class);
        $generator->generate($this->path, 'Acme\\Testing');

        $this->assertFileExists($this->serviceProviderFile);

        $string = '<?php namespace Acme\Testing;

use Illuminate\Support\ServiceProvider;

class TestingServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }

}
';

        $this->assertStringEqualsFile($this->serviceProviderFile, $string);
    }

}

