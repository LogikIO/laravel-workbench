<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\AutoloadWriter;

class AutoloadWriterTest extends TestCase {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $file;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = app(Filesystem::class);
        $this->file = base_path('composer.json');
    }

    /**
     * Returns an associative array with the composer.json contents.
     *
     * @return array
     * @throws FileNotFoundException
     */
    protected function getJsonContents()
    {
        return json_decode($this->filesystem->get($this->file), true);
    }

    public function testItAddsAnAutoloadEntry()
    {
        $writer = app(AutoloadWriter::class);
        $writer->writeAutoloadPsr4(base_path('packages/acme/testing/src'), 'Acme\\Testing');

        $this->assertEquals(
            'packages/acme/testing/src/',
            array_get($this->getJsonContents(), 'autoload.psr-4.Acme\\Testing\\')
        );
    }

    public function testItRemovesAnAutoloadEntry()
    {
        $writer = app(AutoloadWriter::class);
        $writer->removeAutoloadPsr4('Acme\\Testing');

        $this->assertNull(array_get(
            $this->getJsonContents(),
            'autoload.psr-4.Acme\\Testing\\'
        ));
    }
}
