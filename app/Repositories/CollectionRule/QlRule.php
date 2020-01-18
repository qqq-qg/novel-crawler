<?php

namespace App\Repositories\CollectionRule;


/**
 * Class QlRule
 * @author Nacrane
 * @Date: 2020/01/18
 * @Time: 15:43
 * @package App\Repositories\CollectionRule
 * @property string range
 * @property array $rules
 * @property boolean $nextPage
 */
Class QlRule
{
    public function __construct($range = '', $rules = '')
    {
        $this->range = $range;
        $this->rules = $rules;
    }
}
