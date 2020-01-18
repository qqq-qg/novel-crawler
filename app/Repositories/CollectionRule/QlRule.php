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
 * @property integer $page
 */
Class QlRule
{
    public function __construct($range = '', array $rules = [], $nextPage = false, $page = 0)
    {
        $this->range = $range;
        $this->rules = $rules;
        $this->nextPage = $nextPage;
        $this->page = $page;
    }

    public function toArray()
    {
        return [
            'range' => $this->range,
            'rules' => $this->rules,
        ];
    }
}
