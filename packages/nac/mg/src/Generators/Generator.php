<?php

namespace Nac\Mg\Generators;

use Nac\Mg\Compilers\TemplateCompiler;
use Nac\Mg\Filesystem\Filesystem;

class Generator
{

    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    /**
     * Run the generator
     *
     * @param $templatePath
     * @param $templateData
     * @param $filePathToGenerate
     * @throws \Nac\Mg\Filesystem\FileAlreadyExists
     * @throws \Nac\Mg\Filesystem\FileNotFound
     */
    public function make($templatePath, $templateData, $filePathToGenerate)
    {
        // We first need to compile the template,
        // according to the data that we provide.
        $template = $this->compile($templatePath, $templateData, new TemplateCompiler);

        // Now that we have the compiled template,
        // we can actually generate the file.
        $this->file->make($filePathToGenerate, $template);
    }

    /**
     * Compile the file
     *
     * @param $templatePath
     * @param array $data
     * @param TemplateCompiler $compiler
     * @return mixed
     * @throws \Nac\Mg\Filesystem\FileNotFound
     */
    public function compile($templatePath, array $data, TemplateCompiler $compiler)
    {
        return $compiler->compile($this->file->get($templatePath), $data);
    }
}
