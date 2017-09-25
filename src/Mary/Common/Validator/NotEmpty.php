<?php

namespace Mary\Common\Validator;

use Symfony\Component\Validator\Constraints\NotNull;

class NotEmpty extends NotNull
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
}