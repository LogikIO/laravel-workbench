<?php namespace Speelpenning\Workbench;

use Illuminate\Support\ServiceProvider;
use Speelpenning\Workbench\Console\CreatePackage;
use Speelpenning\Workbench\Console\InstallWorkbench;

class WorkbenchServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @param Autoloader $autoloader
     */
    public function boot(Autoloader $autoloader)
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'workbench');
        $autoloader->load();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/workbench.php', 'workbench');

        $this->registerCreationCommand();
    }

    /**
     * Registers the workbench:package command.
     */
    protected function registerCreationCommand()
    {
        $this->app->singleton('command.workbench.package', function ($app) {
            return $app->make(CreatePackage::class);
        });
        $this->commands('command.workbench.package');
    }

}
