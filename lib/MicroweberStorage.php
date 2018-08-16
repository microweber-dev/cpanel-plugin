<?php

class MicroweberStorage
{
    private $file;

    public function __construct($file = '/usr/local/cpanel/microweber/storage/settings.json') {
        $this->file = $file;
    }

    public function read() {
        $data = file_get_contents($this->file);
        return @json_decode($data);
    }
    
    public function save($data) {
        $data = json_encode($data);
        return file_put_contents($this->file, $data);
    }
}