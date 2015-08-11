# Workbench for Laravel 5.1

This package helps you developing packages using the Laravel framework. It handles the following tasks for you:

- Set up a package directory structure in the workbench directory
- Create the following files for you:
    * composer.json
    * A Laravel service provider
    * LICENSE
    * README.md
    * .gitignore
- Add the package to the autoload PSR-4 section of Laravel's composer.json.
    
Supported licenses are: Apache-2.0, GPL-2.0, GPL-3.0, LGPL-2.1 and MIT (default).

## Getting started

Install the package through Composer:

``` CLI
composer require speelpenning/laravel-workbench
```

Add the following service provider to your application config:

``` PHP
Speelpenning\Workbench\WorkbenchServiceProvider::class,
```

The workbench works out of the box. However, to speed up the workflow, it is recommended to take a look at the 
configuration options.

## Creating a new package

In order to create a new package, use the following Artisan command:

``` CLI
php artisan workbench:package
```

Depending on your configuration, the command asks you the following questions:
- Vendor name
- Package name
- Namespace (default: Vendor/Package)
- Describe your package
- Author name (default from configuration)
- Author e-mail address (default from configuration)
- License (default from configuration)

With these questions answered, the basic package structure will be set up. Next, you will be asked if the following 
files should be created for you:

- A Laravel service provider
- A LICENSE file
- A README.md file
- A .gitignore file

## Configuration

The workbench works out of the box, but you may configure some options for your convenience. To avoid publishing the 
config, all configuration can be done through the .env file.

### Your vendor name

Configure your default vendor name with the following line:

``` .env
WORKBENCH_VENDOR=<your-vendor-name-here>
```

### Personal details

Add your personal details to your configuration so they will automatically be added to the composer.json file:

``` .env
WORKBENCH_AUTHOR_NAME=<your-name-here>
WORKBENCH_AUTHOR_EMAIL=<your-email-here>
```

### License

As a default, the MIT license is used for your package. You can change this by using the following line: 

``` .env
WORKBENCH_LICENSE=<your-license-here>
```

You can find the supported licenses on top of this document. Of course you can use any other license you need. It will
be included in the composer.json file, but no LICENSE file will be generated.

### Skip defaults

All questions concerning the previous configuration options can be skipped by adding this line:

``` .env
WORKBENCH_SKIP_DEFAULTS=true
```

### Package directory

The default directory is `base_path('packages')`. If you need to change this for any reason, add the following line
to your configuration:

``` .env
WORKBENCH_PATH=<path-relative-to-base-path>
```
