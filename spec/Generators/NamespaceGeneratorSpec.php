<?php namespace spec\Speelpenning\Workbench\Generators;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Speelpenning\Workbench\Generators\NamespaceGenerator;
use Speelpenning\Workbench\Package;

class NamespaceGeneratorSpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType(NamespaceGenerator::class);
    }

    function it_generates_a_namespace_from_a_package()
    {
        $package = new Package();
        $package->vendor = 'acme-corporation';
        $package->package = 'laravel-unit-testing';

        $this->generate($package)->shouldReturn('AcmeCorporation\\LaravelUnitTesting');
    }
}
