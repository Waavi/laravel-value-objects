<?php

namespace Waavi\ValueObjects\Test\Models;

use Waavi\ValueObjects\Single;

class Email extends Single
{
    public function domain()
    {
        return substr($this->email, strrpos($this->email, '@') + 1);
    }
}
