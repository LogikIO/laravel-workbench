<?php namespace Speelpenning\Workbench\Validators;

use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Validation\Factory;

class PackageValidator {

    /**
     * @var Factory
     */
    protected $validator;

    /**
     * @var array
     */
    protected $rules = [
        'vendor'      => ['required', 'regex:/^[a-z]([a-z0-9\-]*[a-z0-9]+)*$/'],
        'package'     => ['required', 'regex:/^[a-z]([a-z0-9\-]*[a-z0-9]+)*$/'],
        'namespace'   => ['required', 'regex:/^(?:[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*){1}(?:\\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*$/'],
        'description' => ['required', 'string'],
        'authorName'  => ['required', 'string'],
        'authorEmail' => ['required', 'email'],
        'license'     => ['required', 'string'],
    ];

    /**
     * PackageInputValidator constructor.
     *
     * @param Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates the input.
     *
     * @param array $input
     * @throws ValidationException
     */
    public function validate(array $input = [])
    {
        $validation = $this->validator->make($input, $this->rules);

        if ($validation->fails()) {
            throw new ValidationException($validation->errors());
        }
    }

    /**
     * Returns the validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

}
