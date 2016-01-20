<?php

namespace Omnipay\Paysera;


class Param
{
    private $param;
    private $length;
    private $required;

    public function __construct($param, $length = 255, $required = true)
    {
        $this->param = $param;
        $this->length = $length;
        $this->required = $required;
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function isValid()
    {
        if ($this->required && ($this->param === '' || $this->param === null)) {
            return false;
        }
        if (mb_strlen($this->param) > $this->length) {
            return false;
        }
        return true;
    }

    public function getValue()
    {
        return mb_substr($this->param, 0, $this->length);
    }
}