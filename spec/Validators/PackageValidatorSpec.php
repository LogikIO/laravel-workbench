<?php namespace spec\Speelpenning\Workbench\Validators;

use Illuminate\Validation\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Speelpenning\Workbench\Validators\PackageValidator;

class PackageValidatorSpec extends ObjectBehavior
{

    public function let(Factory $validation)
    {
        $this->beConstructedWith($validation);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PackageValidator::class);
    }

    function it_returns_the_validation_rules()
    {
        $this->getRules()->shouldReturn([
            'vendor'      => ['required', 'regex:/^[a-z]([a-z0-9\-]*[a-z0-9]+)*$/'],
            'package'     => ['required', 'regex:/^[a-z]([a-z0-9\-]*[a-z0-9]+)*$/'],
            'namespace'   => ['required', 'regex:/^(?:[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*){1}(?:\\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*$/'],
            'description' => ['required', 'string'],
            'authorName'  => ['required', 'string'],
            'authorEmail' => ['required', 'email'],
            'license'     => ['required', 'string'],
        ]);
    }

}
