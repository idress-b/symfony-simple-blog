<?php

namespace App\Classe;

class Search
{
    public string $string = '';

    public function __toString()
    {
        return $this->string;
    }
}
