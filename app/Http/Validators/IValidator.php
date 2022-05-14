<?php

namespace App\Http\Validators;

interface IValidator
{
    public function id($id);
    public function params($request);
}