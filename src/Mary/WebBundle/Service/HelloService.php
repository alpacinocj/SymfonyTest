<?php

namespace Mary\WebBundle\Service;

class HelloService extends BaseService
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function say()
    {
        return "Hello {$this->name}, Welcome !";
    }
}