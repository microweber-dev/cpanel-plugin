<?php

namespace MicroweberPackages\SharedServerScripts\Shell\Adapters;

interface IShellExecutor
{
    /**
     * @param string $file
     * @param array $args
     * @return mixed
     */
    public function executeFile(string $file, array $args);
}
