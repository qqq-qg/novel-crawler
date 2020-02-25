<?php

namespace Nac\Mg\Filesystem;
class Filesystem
{

    /**
     * Make a file
     *
     * @param $file
     * @param $content
     * @return int
     * @throws FileAlreadyExists
     */
    public function make($file, $content)
    {
        if ($this->exists($file)) {
            throw new FileAlreadyExists;
        }

        return file_put_contents($file, $content);
    }

    /**
     * Determine if file exists
     *
     * @param $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($file);
    }

    /**
     * Fetch the contents of a file
     *
     * @param $file
     * @return string
     * @throws FileNotFound
     */
    public function get($file)
    {
        if (!$this->exists($file)) {
            throw new FileNotFound($file);
        }

        return file_get_contents($file);
    }
}
