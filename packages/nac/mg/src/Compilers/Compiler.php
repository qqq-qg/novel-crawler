<?php

namespace Nac\Mg\Compilers;
interface Compiler
{

    /**
     * Compile the template using
     * the given data
     *
     * @param $template
     * @param $data
     */
    public function compile($template, $data);
}
