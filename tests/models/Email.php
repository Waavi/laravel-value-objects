<?php

namespace Waavi\ValueObjects\Test\Models;

use Waavi\ValueObjects\ValueObject;

class Email extends ValueObject
{
    public function domain()
    {
        return substr($this->email, strrpos($this->email, '@') + 1);
    }
}
