<?php

namespace App\Classe;

use App\Entity\Category;

class Search
{
    #[string]
    public $string = '';

    #[category]
    public $categories = [];
}