<?php

namespace App\Exceptions;

use Exception;

class DBException extends Exception
{
    public $message = "Error connecting to the database";
}
