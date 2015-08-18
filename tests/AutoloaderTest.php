<?php

use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\Autoloader;

class AutoloaderTest extends TestCase {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $autoloadFile;

    public function setUp()
    {
        parent::setUp();

        config([
            'workbench.path' => __DIR__,
        ]);

        $this->filesystem = app(Filesystem::class);
        $this->autoloadFile = config('workbench.path') . DIRECTORY_SEPARATOR . 'autoload.php';

        $this->filesystem->put($this->autoloadFile, '<?php');
    }

    public function tearDown()
    {
        $this->filesystem->delete($this->autoloadFile);
    }

    public function testItLoadsAutoloadFiles()
    {
        ob_start();
        $this->assertEquals(1, app(Autoloader::class)->load());
        ob_end_clean();
    }

}