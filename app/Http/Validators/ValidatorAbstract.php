<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * 
 * @package App\Http\Validators
 */
abstract class ValidatorAbstract
{
    /**
     * 
     * @var string[][]
     */
    protected $paramRules = [];
    /**
     * 
     * @var string[][]
     */
    protected $idRules = [];

    /**
     * 
     * @param Request $request 
     * @return ValidatorAbstract 
     * @throws ValidationException 
     */
    public function params(Request $request): ValidatorAbstract
    {
        $request->expectsJson();
        $validated = Validator::make($request->all(), $this->paramRules);
        if ($validated->fails()) {
            throw new ValidationException($validated);
        }

        return $this;
    }

    /**
     * 
     * @param mixed $id 
     * @return ValidatorAbstract 
     * @throws ValidationException 
     */
    public function id($id): ValidatorAbstract
    {
        $params["id"] = $id;

        $validated = Validator::make($params, $this->idRules);
        if ($validated->fails()) {
            throw new ValidationException($validated);
        }

        return $this;
    }
}
