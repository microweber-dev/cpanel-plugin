<?php
namespace MicroweberPackages\SharedServerScripts\Interfaces;

interface IMicroweberDownloader {

    /**
     * @param string $target
     * @return array
     */
    public function download(string $target);

    /**
     * @return array
     */
    public function getRelease();

}
