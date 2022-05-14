<?php

namespace App\Http\Validators;

use App\Http\Validators\ValidatorAbstract;
/**
 *
 * @package App\Http\Validators
 */
class TaskValidator extends ValidatorAbstract
{
    /**
     *
     * @var string[][]
     */
    protected $paramRules = [
        "title" => ["required", "string", "max:100"],
        "due_date" => ["required", "date", "date_format:Y-m-d", "after:yesterday"],
        "status" => ["required", "in:Pending,Completed"],
    ];
    /**
     *
     * @var string[][]
     */
    protected $idRules = ["id" => ["required", "integer"]];

}
