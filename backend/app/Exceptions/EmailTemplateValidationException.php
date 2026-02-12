<?php

namespace Ciencia\Exceptions;

use Exception;

class EmailTemplateValidationException extends Exception
{
    public array $validationErrors = [];
}