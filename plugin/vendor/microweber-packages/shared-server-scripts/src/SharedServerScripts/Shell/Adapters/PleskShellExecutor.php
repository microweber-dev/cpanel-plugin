<?php
namespace MicroweberPackages\SharedServerScripts\Shell\Adapters;

class PleskShellExecutor implements IShellExecutor
{
    /**
     * @param string $file
     * @param array $args
     * @return mixed
     */
    public function executeFile(string $file, array $args)
    {
        return pm_ApiCli::callSbin($file, $args);
    }
}
