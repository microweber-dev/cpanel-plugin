<?php

namespace MicroweberPackages\SharedServerScripts\FileManager\Adapters;

interface IFileManager
{
    /**
     * @param string $dir
     * @return mixed
     */
    public function isDir(string $dir);

    /**
     * @param string $dir
     * @return mixed
     */
    public function isWritable(string $dir);


    /**
     * @param string $dir
     * @return mixed
     */
    public function scanDir(string $dir);

    /**
     * @param string $file
     * @return mixed
     */
    public function fileExists(string $file);


    /**
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function copy(string $from, string $to);


    /**
     * @param string $file
     * @return mixed
     */
    public function fileExtension(string $file);

}
