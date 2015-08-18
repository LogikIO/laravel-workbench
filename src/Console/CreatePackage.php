<?php namespace Speelpenning\Workbench\Console;

use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\ValidationException;
use Speelpenning\Workbench\AutoloadWriter;
use Speelpenning\Workbench\Exceptions\DirectoryNotCreated;
use Speelpenning\Workbench\Exceptions\FileNotCreated;
use Speelpenning\Workbench\Exceptions\LicenseNotSupported;
use Speelpenning\Workbench\Exceptions\PackageExists;
use Speelpenning\Workbench\Generators\ComposerDotJsonGenerator;
use Speelpenning\Workbench\Generators\GitIgnoreGenerator;
use Speelpenning\Workbench\Generators\LaravelServiceProviderGenerator;
use Speelpenning\Workbench\Generators\LicenseFileGenerator;
use Speelpenning\Workbench\Generators\NamespaceGenerator;
use Speelpenning\Workbench\Generators\PackageGenerator;
use Speelpenning\Workbench\Generators\ReadmeFileGenerator;
use Speelpenning\Workbench\Package;
use Speelpenning\Workbench\Validators\PackageValidator;

class CreatePackage extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workbench:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new package in the workbench.';

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Package
     */
    protected $package;

    /**
     * @var PackageValidator
     */
    protected $validator;

    /**
     * @var PackageGenerator
     */
    protected $packageGenerator;

    /**
     * @var ComposerDotJsonGenerator
     */
    protected $composerDotJsonGenerator;

    /**
     * @var LaravelServiceProviderGenerator
     */
    protected $serviceProviderGenerator;

    /**
     * @var LicenseFileGenerator
     */
    protected $licenceFileGenerator;

    /**
     * @var ReadmeFileGenerator
     */
    protected $readmeFileGenerator;

    /**
     * @var GitIgnoreGenerator
     */
    protected $gitIgnoreGenerator;

    /**
     * @var AutoloadWriter
     */
    protected $autoloadWriter;

    /**
     * @var NamespaceGenerator
     */
    protected $namespaceGenerator;

    /**
     * Indicates if preconfigured defaults should be skipped.
     *
     * @var bool
     */
    protected $skipDefaults = false;

    /**
     * Create a new command instance.
     *
     * @param Repository $config
     * @param Package $package
     * @param PackageValidator $validator
     * @param PackageGenerator $packageGenerator
     * @param ComposerDotJsonGenerator $composerDotJsonGenerator
     * @param LaravelServiceProviderGenerator $serviceProviderGenerator
     * @param LicenseFileGenerator $licenseFileGenerator
     * @param ReadmeFileGenerator $readmeFileGenerator
     * @param GitIgnoreGenerator $gitIgnoreGenerator
     * @param AutoloadWriter $autoloadWriter
     * @param NamespaceGenerator $namespaceGenerator
     */
    public function __construct(Repository $config, Package $package, PackageValidator $validator,
                                PackageGenerator $packageGenerator, ComposerDotJsonGenerator $composerDotJsonGenerator,
                                LaravelServiceProviderGenerator $serviceProviderGenerator,
                                LicenseFileGenerator $licenseFileGenerator, ReadmeFileGenerator $readmeFileGenerator,
                                GitIgnoreGenerator $gitIgnoreGenerator, AutoloadWriter $autoloadWriter,
                                NamespaceGenerator $namespaceGenerator)
    {
        parent::__construct();

        $this->config = $config;
        $this->package = $package;
        $this->validator = $validator;

        $this->packageGenerator = $packageGenerator;
        $this->composerDotJsonGenerator = $composerDotJsonGenerator;
        $this->serviceProviderGenerator = $serviceProviderGenerator;
        $this->licenceFileGenerator = $licenseFileGenerator;
        $this->readmeFileGenerator = $readmeFileGenerator;
        $this->gitIgnoreGenerator = $gitIgnoreGenerator;
        $this->autoloadWriter = $autoloadWriter;
        $this->namespaceGenerator = $namespaceGenerator;

        $this->setPackageDefaults();
        $this->skipDefaults = $this->config->get('workbench.skip_defaults');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        do {
            try {
                $e = $this->collectInput();
                $this->validator->validate($this->package->getProperties());
            }
            catch (ValidationException $e) {
                $this->displayErrors($e->errors());
            }
        } while ($e instanceof ValidationException);

        $this->generatePackage();
        $this->generateComposerDotJsonFile();
        $this->generateLaravelServiceProvider();
        $this->generateLicenseFile();
        $this->generateReadmeFile();
        $this->generateGitIgnoreFile();
        $this->writeAutoload();

        $this->info('Finished creating package ' . $this->package->vendor . '/' . $this->package->package);
    }

    /**
     * Returns an array with all default input values.
     */
    protected function setPackageDefaults()
    {
        $this->package->vendor = $this->config->get('workbench.vendor');
        $this->package->author_name = $this->config->get('workbench.author.name');
        $this->package->author_email = $this->config->get('workbench.author.email');
        $this->package->license = $this->config->get('workbench.license');
    }

    /**
     * Gathers all required input.
     */
    protected function collectInput()
    {
        $this->package->vendor = $this->ask('Vendor name', $this->package->vendor);
        $this->package->package = $this->ask('Package name', $this->package->package);
        $this->package->namespace = rtrim(parent::ask('Namespace', $this->namespaceGenerator->generate($this->package), '\\'));
        $this->package->description = parent::ask('Describe your package', $this->package->description);
        $this->package->authorName = $this->ask('Author name', $this->package->author_name);
        $this->package->authorEmail = $this->ask('Author e-mail address', $this->package->author_email);
        $this->package->license = $this->anticipate('License', $this->licenceFileGenerator->getSupportedLicenses(), $this->package->license);
    }

    /**
     * Prompt the user for input if a default value is not given.
     *
     * @param string $question
     * @param null|string $default
     * @return string
     */
    public function ask($question, $default = null)
    {
        if ($this->skipDefaults and $default) {
            return $default;
        }
        return parent::ask($question, $default);
    }

    /**
     * Prompt the user for input with auto completion if a default value is not given.
     *
     * @param string $question
     * @param array $choices
     * @param null|string $default
     * @return string
     */
    public function anticipate($question, array $choices, $default = null)
    {
        if ($this->skipDefaults and $default) {
            return $default;
        }
        return parent::anticipate($question, $choices, $default);
    }

    /**
     * Displays all errors from a message bag.
     *
     * @param MessageBag $messages
     */
    protected function displayErrors(MessageBag $messages)
    {
        foreach ($messages->all() as $messages) {
            $this->error($messages);
        }
    }

    /**
     * Generates the package structure.
     */
    protected function generatePackage()
    {
        try {
            $this->packageGenerator->generate(
                $this->config->get('workbench.path'),
                $this->package->vendor,
                $this->package->package
            );
        }
        catch (PackageExists $e) {
            $this->error('Package already exists: ' . $e->getMessage());
            exit();
        }
        catch (DirectoryNotCreated $e) {
            $this->error('Directory could not be created: ' . $e->getMessage());
            exit();
        }
    }

    /**
     * Generates the composer.json file.
     */
    protected function generateComposerDotJsonFile()
    {
        try {
            $this->composerDotJsonGenerator->generate($this->packageGenerator->getPackagePath(),
                $this->package->vendor,
                $this->package->package,
                $this->package->description,
                $this->package->license,
                $this->package->authorName,
                $this->package->authorEmail,
                $this->package->namespace
            );
        }
        catch (FileNotCreated $e) {
            $this->error('File not created: ' . $e->getMessage());
        }
    }

    /**
     * Generates a service provider for Laravel.
     */
    protected function generateLaravelServiceProvider()
    {
        if ( ! $this->confirm('Generate a Laravel Service Provider?')) {
            return;
        }

        try {
            $this->serviceProviderGenerator->generate(
                $this->packageGenerator->getSourcePath(),
                $this->package->namespace
            );
        }
        catch (FileNotCreated $e) {
            $this->error('File not created: ' . $e->getMessage());
        }
    }

    /**
     * Generates the license file.
     */
    protected function generateLicenseFile()
    {
        if ( ! $this->confirm('Generate a LICENCE file?')) {
            return;
        }

        try {
            $this->licenceFileGenerator->generate(
                $this->packageGenerator->getPackagePath(),
                $this->package->license,
                $this->package->vendor,
                $this->package->package,
                $this->package->description,
                $this->package->authorName,
                $this->package->authorEmail
            );
        }
        catch (LicenseNotSupported $e) {
            $this->comment('License not supported: ' . $e->getMessage());
        }
        catch (FileNotCreated $e) {
            $this->error('File not created: ' . $e->getMessage());
        }
    }

    /**
     * Generates the readme file.
     */
    protected function generateReadmeFile()
    {
        if ( ! $this->confirm('Generate a README.md file?')) {
            return;
        }

        try {
            $this->readmeFileGenerator->generate(
                $this->packageGenerator->getPackagePath(),
                $this->package->package,
                $this->package->description
            );
        }
        catch (FileNotCreated $e) {
            $this->error('File not created: ' . $e->getMessage());
        }
    }

    /**
     * Generates a .gitignore file.
     */
    protected function generateGitIgnoreFile()
    {
        if ( ! $this->confirm('Generate a .gitignore file?')) {
            return;
        }

        try {
            $this->gitIgnoreGenerator->generate(
                $this->packageGenerator->getPackagePath()
            );
        }
        catch (FileNotCreated $e) {
            $this->error('File not created: ' . $e->getMessage());
        }
    }

    /**
     * Adds the namespace to the autoload PSR-4 section to the composer.json in Laravel's base path.
     */
    protected function writeAutoload()
    {
        try {
            $this->autoloadWriter->writeAutoloadPsr4($this->packageGenerator->getSourcePath(), $this->package->namespace);
        }
        catch (FileNotFoundException $e) {
            $this->error($e->getMessage());
        }
    }
}
