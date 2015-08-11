<?php

namespace spec\Speelpenning\Workbench\Generators;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GitIgnoreGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Speelpenning\Workbench\Generators\GitIgnoreGenerator');
    }
}
